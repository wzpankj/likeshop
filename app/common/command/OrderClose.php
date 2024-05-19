<?php


namespace app\common\command;


use app\common\enum\CouponListEnum;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\logic\OrderLogLogic;
use app\common\model\marketing\CouponList;
use app\common\model\order\Order;
use app\common\model\shop\ShopGoods;
use app\common\model\shop\ShopGoodsItem;
use app\common\server\ConfigServer;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\Log;

class OrderClose extends Command
{
    protected function configure()
    {
        $this->setName('order_close')
            ->setDescription('关闭订单');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            $time = time();
//            $order_cancel_time = ConfigServer::get('transaction', 'unpaid_order_cancel_time', 30) * 60;
            $return_stock = ConfigServer::get('transaction', 'return_stock', 0);

            $model = new Order();
            $order_list = $model->field(true)
                ->where([
                    ['order_status', '=', OrderEnum::ORDER_STATUS_NO_PAID],
                    ['pay_status', '=', PayEnum::UNPAID],
                    ['cancel_time','<',$time]
                ])->with(['orderGoods'])
                ->select()->toArray();

            if (1 == $return_stock ) {
                foreach ($order_list as $order) {
                    foreach ($order['orderGoods'] as $order_goods) {
                        //更新门店商品规格库存
                        ShopGoodsItem::update(['stock'=>Db::raw('stock+' . $order_goods['goods_num'])], ['shop_id' =>$order_goods['shop_id'],'item_id'=>$order_goods['item_id']]);

                        //更新门店商品总库存
                        ShopGoods::update(['total_stock'=>Db::raw('total_stock+' . $order_goods['goods_num'])], ['shop_id' =>$order_goods['shop_id'],'goods_id'=>$order_goods['goods_id']]);

                    }

                    //退回优惠券
                    if($order['coupon_id']){
                        CouponList::where(['user_id'=>$order['user_id'],'order_id'=>$order['id']])
                            ->update(['status'=>CouponListEnum::STATUS_NOT_USE,'order_id'=>'','use_time'=>'','update_time'=>time()]);
                    }
                }
            }

            $order_ids = array_column($order_list,'id');
            // 更新订单状态为关闭
            if ($order_ids) {
                $update_data = [
                    'order_status' => OrderEnum::ORDER_STATUS_DOWN,
                    'update_time'  => $time,
                ];

                $model->where(['id' => $order_ids])->update($update_data);
            }

        } catch (\Exception $e) {
            Log::write('自动关闭订单异常:'.$e->getMessage());
        }
    }
}