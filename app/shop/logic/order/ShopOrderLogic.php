<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\shop\logic\order;


use app\common\basics\Logic;
use app\common\enum\NoticeEnum;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\PayEnum;
use app\common\logic\OrderLogLogic;
use app\common\logic\OrderRefundLogic;
use app\common\logic\IntegralLogic;
use app\common\model\order\Order;
use app\common\model\Printer;
use app\common\model\PrinterConfig;
use app\common\model\PrinterTemplate;
use app\common\model\shop\Shop;
use app\common\model\shop\ShopGoods;
use app\common\model\shop\ShopGoodsItem;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;
use app\common\server\YlyPrinter;
use app\common\server\FeiePrinter;
use Exception;
use think\facade\Cache;
use think\facade\Db;



/**
 * 到店订单逻辑层
 * Class ShopOrderLogic
 * @package app\shop\logic\order
 */
class ShopOrderLogic extends Logic
{
    /**
     * @notes 到店订单数据统计
     * @return int[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/13 6:33 下午
     */
    public static function statistics($get,$shop_id)
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.shop_id', '=', $shop_id];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_YOUSELF_TAKE];

        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //用户信息
        if (isset($get['user_info']) && $get['user_info'] != '') {
            $where[] = ['u.nickname|u.sn', 'like', '%'.$get['user_info'].'%'];
        }
        //商品名称
        if (isset($get['goods_name']) && $get['goods_name'] != '') {
            $where[] = ['og.goods_name', 'like', '%'.$get['goods_name'].'%'];
        }
        //就餐方式
        if (isset($get['dining_type']) && $get['dining_type'] != '') {
            $where[] = ['o.dining_type', '=', $get['dining_type']];
        }
        //付款方式
        if (isset($get['pay_way']) && $get['pay_way'] != '') {
            $where[] = ['o.pay_way', '=', $get['pay_way']];
        }
        //下单时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['o.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['o.create_time', '<=', strtotime($get['end_time'])];
        }

        $result = Order::alias('o')
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->field('o.order_status,o.refund_status')
            ->where($where)
            ->group('o.id')
            ->select()
            ->toArray();

        $all = 0;// 全部
        $wait_pay = 0;// 待付款
        $preparing = 0;// 准备中
        $wait_take = 0;// 待取餐
        $finish = 0;// 已完成
        $close = 0;// 已关闭
        $refund_fail = 0;// 退款失败

        foreach ($result as $val) {
            $all += 1;
            if ($val['order_status'] == OrderEnum::ORDER_STATUS_NO_PAID) {
                $wait_pay += 1;
            }
            if ($val['order_status'] == OrderEnum::ORDER_STATUS_DELIVERY) {
                $preparing += 1;
            }
            if ($val['order_status'] == OrderEnum::ORDER_STATUS_GOODS) {
                $wait_take += 1;
            }
            if ($val['order_status'] == OrderEnum::ORDER_STATUS_COMPLETE) {
                $finish += 1;
            }
            if ($val['order_status'] == OrderEnum::ORDER_STATUS_DOWN) {
                $close += 1;
            }
            if ($val['refund_status'] == OrderEnum::REFUND_FAIL) {
                $refund_fail += 1;
            }
        }

        return [
            'all' => $all,
            'wait_pay' => $wait_pay,
            'preparing' => $preparing,
            'wait_take' => $wait_take,
            'finish' => $finish,
            'close' => $close,
            'refund_fail' => $refund_fail,
        ];
    }

    /**
     * @notes 到店订单列表
     * @param array $get
     * @param $shop_id
     * @return array
     * @author ljj
     * @date 2021/9/14 4:34 下午
     */
    public static function lists($get = [], $shop_id)
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.shop_id', '=', $shop_id];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_YOUSELF_TAKE];

        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //用户信息
        if (isset($get['user_info']) && $get['user_info'] != '') {
            $where[] = ['u.nickname|u.sn', 'like', '%'.$get['user_info'].'%'];
        }
        //商品名称
        if (isset($get['goods_name']) && $get['goods_name'] != '') {
            $where[] = ['og.goods_name', 'like', '%'.$get['goods_name'].'%'];
        }
        //就餐方式
        if (isset($get['dining_type']) && $get['dining_type'] != '') {
            $where[] = ['o.dining_type', '=', $get['dining_type']];
        }
        //付款方式
        if (isset($get['pay_way']) && $get['pay_way'] != '') {
            $where[] = ['o.pay_way', '=', $get['pay_way']];
        }
        //下单时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['o.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['o.create_time', '<=', strtotime($get['end_time'])];
        }

        //订单状态
        $type = $get['type'] ?: 0;
        switch ($type) {
            case 1:      //待付款
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_NO_PAID];
                break;
            case 2:     //准备中
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_DELIVERY];
                break;
            case 3:     //待取餐
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_GOODS];
                break;
            case 4:     //已完成
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_COMPLETE];
                break;
            case 5:     //已关闭
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_DOWN];
                break;
            case 6:     //退款失败
                $where[] = ['o.refund_status', '=', OrderEnum::REFUND_FAIL];
                break;
            default:
                break;
        }

        $field = 'o.id,o.user_id,o.order_sn,o.order_status,o.create_time,o.total_num,o.dining_type,o.is_appoint,o.appoint_time,o.take_code,o.consignee,o.mobile,o.delivery_amount,(o.order_amount - o.delivery_amount) as goods_amount,o.order_amount,o.pay_way,o.pay_status,o.pay_time,o.order_type,o.refund_amount,o.surplus_refund_amount,o.refund_status,o.refund_time,o.zhuohao';

        $count = Order::alias('o')
            ->field($field)
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->with(['order_goods', 'user'])
            ->where($where)
            ->group('o.id')
            ->count();

        $lists = Order::alias('o')
            ->field($field)
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }, 'user' => function($query){
                $query->field('id,avatar,sn,nickname');
            }])
            ->where($where)
            ->append(['order_status_text','pay_way_text','pay_status_text','dining_type_text','refund_status_desc'])
            ->page($get['page'], $get['limit'])
            ->order('o.id desc')
            ->group('o.id')
            ->select()->toArray();

        foreach ($lists as &$list) {
            $list['appoint_time'] = ($list['is_appoint'] == 1) ? $list['appoint_time'] : '立即取餐';
            foreach ($list['order_goods'] as &$val) {
                $val['image'] = $val['goods_snap']['image'];
                if (!empty($val['goods_snap']['material_name'])) {
                    $val['spec_value'] = $val['goods_snap']['spec_value_str'].','.implode(',',$val['goods_snap']['material_name']);
                }else {
                    $val['spec_value'] = $val['goods_snap']['spec_value_str'];
                }

                $val['total_price'] = $val['goods_snap']['total_price'];
                unset($val['goods_snap']);
            }
        }

        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 导出列表
     * @param $get
     * @param $admin_id
     * @return array
     * @author ljj
     * @date 2021/9/16 2:35 下午
     */
    public static function exportFile($get,$shop_id)
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.shop_id', '=', $shop_id];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_YOUSELF_TAKE];

        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //用户信息
        if (isset($get['user_info']) && $get['user_info'] != '') {
            $where[] = ['u.nickname|u.sn', 'like', '%'.$get['user_info'].'%'];
        }
        //商品名称
        if (isset($get['goods_name']) && $get['goods_name'] != '') {
            $where[] = ['og.goods_name', 'like', '%'.$get['goods_name'].'%'];
        }
        //就餐方式
        if (isset($get['dining_type']) && $get['dining_type'] != '') {
            $where[] = ['o.dining_type', '=', $get['dining_type']];
        }
        //付款方式
        if (isset($get['pay_way']) && $get['pay_way'] != '') {
            $where[] = ['o.pay_way', '=', $get['pay_way']];
        }
        //下单时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['o.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['o.create_time', '<=', strtotime($get['end_time'])];
        }

        //订单状态
        $type = $get['type'] ?: 0;
        switch ($type) {
            case 1:      //待付款
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_NO_PAID];
                break;
            case 2:     //准备中
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_DELIVERY];
                break;
            case 3:     //待取餐
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_GOODS];
                break;
            case 4:     //已完成
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_COMPLETE];
                break;
            case 5:     //已关闭
                $where[] = ['o.order_status', '=', OrderEnum::ORDER_STATUS_DOWN];
                break;
            case 6:     //退款失败
                $where[] = ['o.refund_status', '=', OrderEnum::REFUND_FAIL];
                break;
            default:
                break;
        }

        $lists = Order::alias('o')
            ->field('o.id,o.user_id,o.order_sn,o.order_status,o.create_time,o.total_num,o.dining_type,o.is_appoint,o.appoint_time,o.take_code,o.consignee,o.mobile,o.delivery_amount,(o.order_amount - o.delivery_amount) as goods_amount,o.order_amount,o.pay_way,o.pay_status,o.pay_time,o.order_type')
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }, 'user' => function($query){
                $query->field('id,avatar,sn,nickname');
            }])
            ->where($where)
            ->append(['order_status_text','pay_way_text','pay_status_text','dining_type_text'])
            ->order('o.id desc')
            ->group('o.id')
            ->select()->toArray();

        $exportTitle = ['ID', '订单编号', '订单状态', '下单时间', '会员编号', '会员昵称', '商品信息', '商品数量', '就餐方式', '取餐时间', '联系人', '联系方式', '配送费用', '商品金额', '应付金额',
            '支付方式', '支付状态', '支付时间'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            $goods_info = [];
            foreach ($item['order_goods'] as $val) {
                if (!empty($val['goods_snap']['material_name'])) {
                    $spec_value = $val['goods_snap']['spec_value_str'].','.implode(',',$val['goods_snap']['material_name']);
                }else {
                    $spec_value = $val['goods_snap']['spec_value_str'];
                }
                $goods_info[] = '[商品名称:'.$val['goods_name'].';商品规格:'.$spec_value.';单价:'.$val['goods_price'].';实付:'.$val['goods_price'].';数量:'.$val['goods_num'].';总计实付:'.$val['goods_snap']['total_price'].']';
            }
            if (!empty($goods_info)) {
                $goods_info = implode(',',$goods_info);
            }

            $exportData[] = [$item['id'], $item['order_sn'], $item['order_status_text'], $item['create_time'], $item['user']['sn'], $item['user']['nickname'], $goods_info, $item['total_num'], $item['dining_type_text'], $item['appoint_time'], $item['consignee'], $item['mobile'], $item['delivery_amount'], $item['goods_amount'], $item['order_amount'], $item['pay_way_text'], $item['pay_status_text'], $item['pay_time']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'到店订单列表'.date('Y-m-d H:i:s',time())];
    }

    /**
     * @notes 取消订单
     * @param $post
     * @param $admin_id
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 5:39 下午
     */
    public static function cancel($post, $admin_id)
    {
        Db::startTrans();
        try {
            foreach ($post['order_ids'] as $val) {
                $order = Order::where(['id' => $val], ['orderGoods'])->find();
                //取消订单
                OrderRefundLogic::cancelOrder($val, OrderLogEnum::TYPE_SHOP, $admin_id);
                //已支付的订单,取消,退款
                if ($order['pay_status'] == PayEnum::ISPAID) {
                    //订单退款
                    OrderRefundLogic::refund($order, $order['order_amount'], $order['order_amount']);
                }

                $return_stock = ConfigServer::get('transaction', 'return_stock', 0);
                if (1 == $return_stock ) {
                    foreach ($order['orderGoods'] as $order_goods) {
                        //更新门店商品规格库存
                        ShopGoodsItem::update(['stock'=>Db::raw('stock+' . $order_goods['goods_num'])], ['shop_id' =>$order_goods['shop_id'],'item_id'=>$order_goods['item_id']]);

                        //更新门店商品总库存
                        ShopGoods::update(['total_stock'=>Db::raw('total_stock+' . $order_goods['goods_num'])], ['shop_id' =>$order_goods['shop_id'],'goods_id'=>$order_goods['goods_id']]);

                    }
                }

                //订单日志
                OrderLogLogic::record(
                    OrderLogEnum::TYPE_SHOP,
                    OrderLogEnum::SHOP_CANCEL_ORDER,
                    $val,
                    $admin_id,
                    $order['shop_id']
                );
            }
            //取消订单后续操作
            OrderLogLogic::cancelOrderSubsequentHandle($order);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            if ($order['pay_status'] == PayEnum::ISPAID) {
                //增加退款失败记录
                OrderRefundLogic::addErrorRefund($order, $e->getMessage());
            }
            return $e->getMessage();
        }
    }

    /**
     * @notes 确认取餐
     * @param $order_id
     * @param $admin_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 6:44 下午
     */
    public static function confirm($post, $admin_id)
    {
        foreach ($post['order_ids'] as $val) {

            

            $order = Order::where(['del' => 0, 'id' => $val])->find();
            $order->order_status = OrderEnum::ORDER_STATUS_COMPLETE;
            $order->update_time = time();
            $order->confirm_take_time = time();
            $order->save();

            //订单日志
            OrderLogLogic::record(
                OrderLogEnum::TYPE_SHOP,
                OrderLogEnum::SHOP_CONFIRM_ORDER,
                $val,
                $admin_id,
                $order->shop_id
            );

            //进行积分操作 不用验证状态 本来有验证的
            (new IntegralLogic())->dealOrderIntegralConfirm($val);
            // exit;
        }
    }

    /**
     * @notes 通知取餐
     * @param $post
     * @param $admin_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 7:04 下午
     */
    public static function notice($post, $admin_id)
    {
        $shop_list = Shop::column('*','id');
        foreach ($post['order_ids'] as $val) {
            $order = Order::where(['del' => 0, 'id' => $val])->find();
            $order->order_status = OrderEnum::ORDER_STATUS_GOODS;
            $order->update_time = time();
            $order->save();

            //订单日志
            OrderLogLogic::record(
                OrderLogEnum::TYPE_SHOP,
                OrderLogEnum::SHOP_NOTICE_ORDER,
                $val,
                $admin_id,
                $order->shop_id
            );
            $shop = $shop_list[$order->shop_id] ?? [];
            if($shop){
                //取餐通知
                event('Notice', [
                    'scene'     => NoticeEnum::TAKEFOOD_NOTICE,
                    'params'    => [
                        'user_id'       => $order['user_id'],
                        'order_id'      => $order->id,
                        'shop_name'     => $shop['name'],
                        'shop_address'  => $shop['address'],
                        'take_code'     => $order['take_code'],
                    ]
                ]);
            }


        }
    }

    /**
     * @notes 商家备注
     * @param $post
     * @param $type
     * @param $admin_id
     * @return array|bool|\think\Model|void
     * @author ljj
     * @date 2021/9/17 10:13 上午
     */
    public static function remarks($post,$type,$admin_id)
    {
        if ($type === 'get') {
            return Order::field('id,order_remarks')
                ->where(['id' => $post['id']])
                ->findOrEmpty();
        }elseif($type === 'post') {
            $order = Order::find($post['id']);
            $order->order_remarks = $post['order_remarks'];
            $order->update_time = time();
            $order->save();

            //订单日志
            OrderLogLogic::record(
                OrderLogEnum::TYPE_SHOP,
                OrderLogEnum::SHOP_REMARKS_ORDER,
                $post['id'],
                $admin_id,
                $order->shop_id
            );

            return true;
        }
    }

    /**
     * @notes 小票打印
     * @param $post
     * @return string|void
     * @author ljj
     * @date 2021/9/17 10:30 上午
     */
    public static function print_bak($post)
    {
        try {
            //打印机配置
            $printer_config = PrinterConfig::where(1,1)->find();
            //打印机列表
            $printer_list = Printer::with(['printer_template'])->where(['printer_config_id' => $printer_config['id'], 'del' => 0,'status'=>1])->select();

            if (empty($printer_config) || empty($printer_list)) {
                throw new Exception('请先配置打印机');
            }
            $yly_print = new YlyPrinter($printer_config['client_id'], $printer_config['client_secret']);

            //获取打印订单
            $order = Order::with(['user', 'order_goods','shop'])
                ->where('id', $post['id'])
                ->append(['delivery_address','dining_type_text'])
                ->find()
                ->toArray();
            $order['shop_name'] = $order['shop']['name'];
            $order['goods_price'] = $order['total_amount'] - $order['delivery_amount'];
            foreach ($order['order_goods'] as &$val) {
                $info = $val['goods_snap'];
                $val['name'] = $info['name'];
                $val['spec_value_str'] = $info['spec_value_str'];
                $val['goods_image'] = empty($info['spec_image']) ? $info['image'] : $info['spec_image'];
            }
            $yly_print->ylyPrint($printer_list, $order);
            return true;

        } catch (Exception $e) {
            $msg = json_decode($e->getMessage(), true);
            if ($msg && isset($msg['error'])) {
                return '易联云：' . $msg['error_description'];
            }
            if (18 === $e->getCode()) {
                //todo token过期重新拿
                Cache::tag('yly_printer')->clear();
            }
            return '易联云：' . $e->getMessage();
        }
    }


    /**
     * @notes 小票打印-飞蛾  网站后台小票打印
     * @param $post
     * @return string|void
     * @author ljj
     * @date 2021/9/17 10:30 上午
     */
    public static function print($post)
    {
        try {
            //打印机配置
            $printer_config = PrinterConfig::where(1,1)->find();
            //打印机列表
            $printer_list = Printer::with(['printer_template'])->where(['printer_config_id' => $printer_config['id'], 'del' => 0,'status'=>1])->select();

            if (empty($printer_config) || empty($printer_list)) {
                throw new Exception('请先配置打印机');
            }
            $feie=new FeiePrinter;
            
            define('USER', $printer_config['client_id']);  //*必填*：飞鹅云后台注册账号
            define('UKEY', $printer_config['client_secret']);  //*必填*: 飞鹅云后台注册账号后生成的UKEY 【备注：这不是填打印机的KEY】
            //以下参数不需要修改
            define('IP','api.feieyun.cn');      //接口IP或域名
            define('PORT',80);            //接口IP端口
            define('PATH','/Api/Open/');    //接口路径

   //          DB::name('debug_log')->insert(['spec'    => "622".serialize(json_encode(USER))]);
			// DB::name('debug_log')->insert(['spec'    => "623".serialize(json_encode(UKEY))]);
            //获取打印订单
            $order = Order::with(['user', 'order_goods','shop'])
                ->where('id', $post['id'])
                ->append(['delivery_address','dining_type_text'])
                ->find()
                ->toArray();
            $order['shop_name'] = $order['shop']['name'];
            $order['goods_price'] = $order['total_amount'] - $order['delivery_amount'];
            foreach ($order['order_goods'] as &$val) {
                $info = $val['goods_snap'];
                $val['name'] = $info['name'];
                $val['spec_value_str'] = $info['spec_value_str'];
                $val['goods_image'] = empty($info['spec_image']) ? $info['image'] : $info['spec_image'];
            }
            $feie->ylyPrint($printer_list, $order);
            return true;

        } catch (Exception $e) {
            $msg = json_decode($e->getMessage(), true);
            if ($msg && isset($msg['error'])) {
                return '云打印：' . $msg['error_description'];
            }
            if (18 === $e->getCode()) {
                //todo token过期重新拿
                // Cache::tag('yly_printer')->clear();
            }
            return '云打印：' . $e->getMessage();
        }
    }

    /**
     * @notes 订单详情
     * @param $id
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/17 10:37 上午
     */
    public static function getDetail($id)
    {
        $result = Order::with(['user', 'order_goods', 'order_log' => function($query){
            $query->append(['channel_text']);
        }])
            ->where('id', $id)
            ->append(['pay_status_text', 'order_status_text', 'pay_way_text', 'order_type_text', 'dining_type_text'])
            ->find();

        $result['goods_price'] = 0;
        foreach ($result['order_goods'] as &$order_goods) {
            $order_goods['goods_image'] = !empty($order_goods['image']) ? UrlServer::getFileUrl($order_goods['image']) : '';
            $order_goods['pay_price'] = $order_goods['goods_snap']['total_price'] / $order_goods['goods_snap']['goods_num'];
            $order_goods['total_price'] = $order_goods['goods_snap']['total_price'];
            if (!empty($order_goods['goods_snap']['material_name'])) {
                $order_goods['spec_value'] = $order_goods['goods_snap']['spec_value_str'].','.implode(',',$order_goods['goods_snap']['material_name']);
            }else {
                $order_goods['spec_value'] = $order_goods['goods_snap']['spec_value_str'];
            }

            $result['goods_price'] += $order_goods['total_price'];
        }

        //商品实付金额
        $result['goods_pay_amount'] = $result['goods_price'] - $result['discount_amount'] ?? 0;
        if ($result['goods_pay_amount'] < 0) {
            $result['goods_pay_amount'] = 0;
        }
        //订单实付金额
        $result['total_pay_amount'] = $result['order_amount'] - $result['discount_amount'];
        if ($result['total_pay_amount'] < 0) {
            $result['total_pay_amount'] = 0;
        }

        return $result;
    }

    /**
     * @notes 获取订单退款详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/18 3:30 下午
     */
    public static function getRefundDetail($id)
    {
        $result = Order::where('id',$id)->field('id,user_id,order_amount,refund_amount,surplus_refund_amount')->find()->toArray();
        $result['surplus_refund_amount'] = ($result['surplus_refund_amount'] === null) ? $result['order_amount'] : $result['surplus_refund_amount'];
        $result['refund_amount'] = ($result['refund_amount'] === null) ? '0.00' : $result['refund_amount'];
        return $result;
    }

    /**
     * @notes 订单退款
     * @param $post
     * @param $admin_id
     * @return bool|string
     * @author ljj
     * @date 2021/9/18 6:41 下午
     */
    public static function refund($post,$admin_id)
    {
        Db::startTrans();
        try {
            $order = Order::where('id',$post['id'])->find()->toArray();

            //订单退款
            OrderRefundLogic::refund($order, $order['order_amount'], $post['refund_amount'], ['refund_type'=>$post['refund_type'],'refund_way'=>$post['refund_way'],'refund_remark'=>$post['refund_remark']]);

            //订单日志
            OrderLogLogic::record(
                OrderLogEnum::TYPE_SHOP,
                OrderLogEnum::SHOP_REFUND_ORDER,
                $post['id'],
                $admin_id,
                $order['shop_id']
            );

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}