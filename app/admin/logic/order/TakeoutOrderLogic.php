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

namespace app\admin\logic\order;


use app\common\basics\Logic;
use app\common\enum\OrderEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\PayEnum;
use app\common\logic\OrderLogLogic;
use app\common\logic\OrderRefundLogic;
use app\common\model\order\Order;
use app\common\model\Printer;
use app\common\model\PrinterConfig;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;
use app\common\server\YlyPrinter;
use think\Exception;
use think\facade\Cache;
use think\facade\Db;

/**
 * 外卖订单逻辑层
 * Class TakeoutOrderLogic
 * @package app\shop\logic\order
 */
class TakeoutOrderLogic extends Logic
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
    public static function statistics($get)
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_TAKE_AWAY];

        //门店信息
        if (isset($get['shop_info']) && $get['shop_info'] != '') {
            $where[] = ['s.name', 'like', '%'.$get['shop_info'].'%'];
        }

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
            ->field('o.order_status,o.refund_status')
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('shop s', 's.id = o.shop_id')
            ->where($where)
            ->order('o.id desc')
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
     * @return array
     * @author ljj
     * @date 2021/9/14 4:34 下午
     */
    public static function lists($get = [])
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_TAKE_AWAY];

        //门店信息
        if (isset($get['shop_info']) && $get['shop_info'] != '') {
            $where[] = ['s.name', 'like', '%'.$get['shop_info'].'%'];
        }

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
            case 3:     //配送中
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

        $field = 'o.id,o.user_id,o.order_sn,o.order_status,o.create_time,o.total_num,o.dining_type,o.take_code,o.consignee,o.mobile,o.delivery_amount,(o.order_amount - o.delivery_amount) as goods_amount,o.order_amount,o.pay_way,o.pay_status,o.pay_time,o.order_type,o.transaction_id,o.address_snap,o.refund_amount,o.surplus_refund_amount,o.refund_status,o.refund_time,s.shop_sn,s.name as shop_name';

        $count = Order::alias('o')
            ->field($field)
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods', 'user'])
            ->where($where)
            ->group('o.id')
            ->count();

        $lists = Order::alias('o')
            ->field($field)
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }, 'user' => function($query){
                $query->field('id,avatar,sn,nickname');
            }])
            ->where($where)
            ->append(['order_status_text','pay_way_text','pay_status_text','dining_type_text','delivery_address','refund_status_desc'])
            ->page($get['page'], $get['limit'])
            ->order('o.id desc')
            ->group('o.id')
            ->select()->toArray();

        foreach ($lists as &$list) {
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
    public static function exportFile($get)
    {
        $where[] = ['o.del', '=', 0];
        $where[] = ['o.order_type', '=', OrderEnum::ORDER_TYPE_TAKE_AWAY];

        //门店信息
        if (isset($get['shop_info']) && $get['shop_info'] != '') {
            $where[] = ['s.name', 'like', '%'.$get['shop_info'].'%'];
        }

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
            ->field('o.id,o.user_id,o.order_sn,o.order_status,o.create_time,o.total_num,o.dining_type,o.take_code,o.consignee,o.mobile,o.delivery_amount,(o.order_amount - o.delivery_amount) as goods_amount,o.order_amount,o.pay_way,o.pay_status,o.pay_time,o.order_type,o.transaction_id,s.shop_sn,s.name as shop_name')
            ->join('user u', 'u.id = o.user_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }, 'user' => function($query){
                $query->field('id,avatar,sn,nickname');
            }])
            ->where($where)
            ->append(['order_status_text','pay_way_text','pay_status_text','dining_type_text','delivery_address'])
            ->order('o.id desc')
            ->group('o.id')
            ->select()->toArray();

        $exportTitle = ['ID', '门店信息', '订单编号', '订单状态', '下单时间', '会员编号', '会员昵称', '商品信息', '商品数量', '收货方式', '收货人', '手机号码', '收货地址', '配送费用', '商品金额', '应付金额',
            '支付方式', '支付状态', '支付时间', '支付流水号'];
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

            $exportData[] = [$item['id'],$item['shop_sn'].'-'.$item['shop_name'], $item['order_sn'], $item['order_status_text'], $item['create_time'], $item['user']['sn'], $item['user']['nickname'], $goods_info, $item['total_num'], $item['dining_type_text'], $item['consignee'], $item['mobile'], $item['delivery_address'], $item['delivery_amount'], $item['goods_amount'], $item['order_amount'], $item['pay_way_text'], $item['pay_status_text'], $item['pay_time'], $item['transaction_id']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'到店订单列表'.date('Y-m-d H:i:s',time())];
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
            ->append(['pay_status_text', 'order_status_text', 'pay_way_text', 'order_type_text', 'dining_type_text','delivery_address','refund_status_desc'])
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
}