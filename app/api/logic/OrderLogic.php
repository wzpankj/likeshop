<?php

namespace app\api\logic;
use app\api\server\OrderServer;
use app\common\{basics\Logic,
    enum\OrderEnum,
    enum\OrderLogEnum,
    enum\ShopGoodsEnum,
    logic\OrderLogLogic,
    logic\OrderRefundLogic,
    model\Cart,
    model\order\Order,
    model\order\OrderGoods,
    model\shop\ShopGoods,
    model\shop\ShopGoodsItem,
    server\ConfigServer,
    server\UrlServer};
use think\{
    facade\Db,
    Exception
};
use app\common\server\MnpServer;

/**
 * 订单逻辑层
 * Class OrderLogic
 * @package app\api\logic
 */
class OrderLogic extends Logic
{


    /**
     * @notes 下单接口
     * @param $post
     * @return array|bool
     * @author cjhao
     * @date 2021/9/16 15:32
     * 下单流程：1、验证订单信息（checkOrderBaseData）
     *         2、计算价格（calculateOrder）
     *         3、下单操作（sumbitOrder）
     *         4、扣减库存（updateStock）
     *         5、删除购物车（delCart）
     */
    public static function sumbitOrder($post)
    {

        /*$nimei=new MnpServer();
        $nimei->createQrCode();
        exit;*/
        Db::startTrans();
        try {
            $order_server = new OrderServer($post);
            $order_server->checkOrderBaseData()->calculateOrder();
            $action = $post['action'] ?? '';

            if('submit' !== $action){
                return [
                    'shop'              => $order_server->getShopInfo(),
                    'user_address'      => $order_server->getUserAddress(),
                    'goods'             => $order_server->getOrderGoods(),
                    'goods_num'         => $order_server->getOrderNum(),
                    'goods_amount'      => $order_server->getGoodsAmount(),     //商品总价
                    'delivery_amount'   => $order_server->getDeliveryAmount(),  //商品运费
                    // 'dabao_amount'      => $order_server->getDabaoAmount(),  //商品运费
                    'discount_amount'   => $order_server->getDiscountAmount(),  //优惠金额
                    'discount_amount_jifen'   => $order_server->getDiscountAmountIntegral(),  //积分优惠金额
                    'isuseintegral'     => $order_server->isUseIntegral(),  //积分使用规则
                    'order_amount'      => $order_server->getOrderAmount(),     //订单应付
                    'total_amount'      => $order_server->getTotalAmount(),     //订单总价
                ];
            }
            $order = $order_server
                    ->sumbitOrder()     //下单操作
                    ->updateStock()     //更新库存
                    ->delCart()        //删除购物车
//                    ->sendNotice()      //发送通知
                    ->getOrderInfo();   //获取订单信息

            Db::commit();

            return [
                'order_id'  => $order->id,
                'order_sn'  => $order->order_sn,
            ];

        } catch (Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 获取订单
     * @param $id
     * @return bool
     * @author cjhao
     * @date 2021/9/16 15:32
     */
    public static function getOrderDetail($id,$user_id)
    {
        $detail = Order::with(['order_goods','shop'])
                ->withoutField('confirm_take_time,transaction_id,update_time,del')
                ->append(['order_type_text','order_status_text','pay_btn','cancel_btn','again_btn','delivery_address'])
                ->where(['id'=>$id,'user_id'=>$user_id])
                ->find();

        $detail['shop']['address_detail'] = $detail->shop->province_name.$detail->shop->city_name.$detail->shop->district_name.$detail->shop->address;
        return $detail->toArray();

    }

    /**
     * @notes 取消订单
     * @param $order_id
     * @param $user_id
     * @return bool|string
     * @author cjhao
     * @date 2021/9/22 9:33
     */
    public static function cancel($order_id,$user_id)
    {
        try{
            Db::startTrans();
            if(empty($order_id)){
                throw new Exception('请选择订单');
            }

            $order = Order::where(['id'=>$order_id,'user_id'=>$user_id])
                    ->find();

            if(empty($order)){
                throw new Exception('订单错误');
            }
            if($order->order_status == OrderEnum::ORDER_STATUS_DOWN){
                throw new Exception('订单已取消，请勿重复操作');
            }
            if($order->order_status > OrderEnum::ORDER_STATUS_DELIVERY ){
                throw new Exception('订单已制作完成，不能取消');
            }
            $order_cancel_time = ConfigServer::get('transaction', 'paid_order_cancel_time', 5) * 60; //已支付允许取消时长（分钟）
            $now = time();
            if(OrderEnum::PAY_STATUS_PAY == $order->pay_status){
                if( $now - strtotime($order->create_time) > $order_cancel_time){
                    throw new Exception('订单制作中，不能取消');
                }

                //todo 已支付订单，发起退款操作
                OrderRefundLogic::refund($order, $order['order_amount'], $order['order_amount']);
            }
            //更新订单取消状态
            $order->order_status = OrderEnum::ORDER_STATUS_DOWN;
            $order->cancel_time  = $now;
            $order->save();
            //取消订单是否退回库存,todo 下单时候没有记录这个字段，取消订单时候直接拿配置
            $return_stock = ConfigServer::get('transaction', 'return_stock', 0);
            if($return_stock){
                $order_goods_list = OrderGoods::where(['order_id'=>$order->id])
                    ->select();
                //回退库存
                foreach ($order_goods_list as $order_goods){
                    //更新门店商品规格库存
                    ShopGoodsItem::where(['shop_id'=>$order_goods->shop_id,'item_id'=>$order_goods->item_id])
                        ->update(['stock'=>Db::raw('stock+'.$order_goods->goods_num)]);
                    //更新门店商品总库存
                    ShopGoods::where(['shop_id'=>$order_goods->shop_id,'goods_id'=>$order_goods->goods_id])
                        ->update(['total_stock'=>Db::raw('total_stock+'.$order_goods->goods_num)]);
                }
            }

            //记录取消订单日志
            OrderLogLogic::record(OrderLogEnum::TYPE_USER,
                        OrderLogEnum::USER_CANCEL_ORDER,
                                $order->id,
                                $order->user_id,
                                $order->shop_id
                                );
            //取消订单后续操作
            OrderLogLogic::cancelOrderSubsequentHandle($order);

            Db::commit();
            return true;

        }catch (Exception $e){
            Db::rollback();
            return $e->getMessage();
        }
    }


    /**
     * @notes 订单列表
     * @param $user_id
     * @param $type
     * @param $page_no
     * @param $page_size
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/24 5:10 下午
     */
    public static function lists($user_id,$type,$page_no,$page_size)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['user_id', '=', $user_id];
        switch ($type) {
            //当前订单
            case 'current':
                $where[] = ['order_status', '<', OrderEnum::ORDER_STATUS_COMPLETE];
                break;
            //历史订单
            case 'history':
                $where[] = ['order_status', '>', OrderEnum::ORDER_STATUS_GOODS];
            default:
                break;

        }

        $count = Order::where($where)->count();

        $lists = Order::field('id,shop_id,pay_status,order_status,order_type,order_amount,total_num,create_time,cancel_time')
            ->with(['order_goods' => function($query){
                $query->field('order_id,shop_id,image,item_id,material_ids,goods_num');
            },'shop'])
            ->where($where)
            ->append(['order_status_text','pay_btn','cancel_btn','again_btn'])
            ->page($page_no, $page_size)
            ->order('id','desc')
            ->select()
            ->toArray();

        foreach ($lists as &$list) {
            foreach ($list['order_goods'] as &$goods) {
                $goods['image'] =  UrlServer::getFileUrl($goods['image']);
            }

            $list['order_cancel_time'] = ConfigServer::get('transaction', 'unpaid_order_cancel_time', 5) * 60;
        }

        return [
            'lists' => $lists,
            'page_no' => $page_no,
            'page_size' => $page_size,
            'count' => $count,
            'more' => is_more($count, $page_no, $page_size)
        ];
    }


    /**
     * @notes 再来一单
     * @param $post
     * @param $user_id
     * @return bool
     * @author ljj
     * @date 2021/9/26 2:36 下午
     */
    public static function again($post, $user_id)
    {
        if (!is_array($post) || empty($post)) {
            self::$error = '数据格式不正确';
            return false;
        }
        foreach ($post as $val) {
            $shop_goods = ShopGoods::alias('SG')
                ->join('ShopGoodsItem SGI','SG.id = SGI.shop_goods_id')
                ->where(['status'=>ShopGoodsEnum::STATUS_SHELVES,'SGI.item_id'=>$val['item_id']])
                ->field('SGI.goods_id,SGI.item_id')
                ->find();

            if(empty($shop_goods)){
                continue;
            }

            $goods_info = \app\common\logic\GoodsLogic::getGoodsPrice($val['shop_id'],[$shop_goods->goods_id]);
            $goods_item = $goods_info['goods_item'][$shop_goods->goods_id][$shop_goods->item_id] ?? [];

            $stock = $goods_item['stock'] ?? 0;
            $cart = Cart::where(['user_id' => $user_id, 'item_id' => $val['item_id']])->findOrEmpty();
            //加上购物车的数量
            if(!$cart->isEmpty() && empty(array_diff($cart['material_ids'],$val['material_ids']))){
                $num = $val['num'] + $cart->num;
            }

            if($stock < $num){
                continue;
            }

            //添加购物车
            CartLogic::add($val, $user_id);
        }

        return true;
    }

}
