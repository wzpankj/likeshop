<?php


namespace app\shop\logic\finance;


use app\common\basics\Logic;
use app\common\enum\OrderEnum;
use app\common\enum\OrderRefundEnum;
use app\common\enum\PayEnum;
use app\common\model\order\Order;
use app\common\model\order\OrderRefund;

/**
 * 门店财务中心逻辑层
 * Class CenterLogic
 * @package app\shop\logic\finance
 */
class CenterLogic extends Logic
{
    /**
     * @notes 数据统计
     * @param int $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/27 6:52 下午
     */
    public static function statistics($shop_id = 0)
    {
        $all_order = 0;
        $shop_order = 0;
        $takeout_order = 0;
        $sale_amount = 0;
        $shop_sale_amount = 0;
        $takeout_sale_amount = 0;
        $refund_amount = 0;
        $wait_refund_amount = 0;

        $order_lists = Order::where(['del'=>0,'pay_status'=>PayEnum::ISPAID,'shop_id'=>$shop_id])->select()->toArray();

        foreach ($order_lists as $order) {
            $all_order += 1;
            $sale_amount += $order['order_amount'];
            if ($order['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $shop_order += 1;
                $shop_sale_amount += $order['order_amount'];
            }
            if ($order['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                $takeout_order += 1;
                $takeout_sale_amount += $order['order_amount'];
            }
        }

        $refund_lists = OrderRefund::where(['shop_id'=>$shop_id])->select()->toArray();

        foreach ($refund_lists as $refund) {
            if ($refund['refund_status'] == OrderRefundEnum::REFUND_STATUS_COMPLETE) {
                $refund_amount += $refund['refund_amount'];
            }
            if ($refund['refund_status'] == OrderRefundEnum::REFUND_STATUS_ING) {
                $wait_refund_amount += $refund['refund_amount'];
            }
        }


        return [
            'all_order' => $all_order, //已结算成交订单数
            'shop_order' => $shop_order, //已结算营业额
            'takeout_order' => $takeout_order, //待结算营业额
            'sale_amount' => $sale_amount, //已结算分销佣金金额
            'shop_sale_amount' => $shop_sale_amount, //已结算入账金额
            'takeout_sale_amount' => $takeout_sale_amount, //已结算交易服务费
            'refund_amount' => $refund_amount, //已结算入账金额
            'wait_refund_amount' => $wait_refund_amount, //已结算交易服务费
        ];
    }

    /**
     * @notes 财务中心
     * @param $get
     * @param $shop_id
     * @return array
     * @author ljj
     * @date 2021/9/28 10:37 上午
     */
    public static function center($get, $shop_id)
    {
        $start_time = strtotime(date('Y-m-d H:i:s', strtotime(date("Y-m-d", strtotime("-7 day")))));
        $end_time = strtotime(date('Y-m-d 23:59:59', time()));
        $where[] = ['del', '=', 0];
        $where[] = ['shop_id', '=', $shop_id];
        $where[] = ['pay_status', '=', PayEnum::ISPAID];
        if (!empty($get['start_time']) and $get['start_time']) {
            $start_time = strtotime($get['start_time']);
        }

        if (!empty($get['end_time']) and $get['end_time']) {
            $end_time = strtotime($get['end_time']);
        }

        $lists = [];
        for($i = $start_time; $i <= $end_time; $i += 86400) {
            $result = Order::field('id,order_amount,order_type,refund_amount')
                ->where($where)
                ->whereDay('create_time',date('Y-m-d',$i))
                ->select()
                ->toArray();

            if (empty($result)) {
                continue;
            }

            $sale_amount = 0;
            $all_order = 0;
            $shop_sale_amount = 0;
            $takeout_sale_amount = 0;
            $shop_order = 0;
            $takeout_order = 0;
            $refund_amount = 0;
            foreach ($result as &$val) {
                $sale_amount += $val['order_amount'];
                $all_order += 1;
                $refund_amount += $val['refund_amount'];
                if ($val['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                    $shop_sale_amount += $val['order_amount'];
                }
                if ($val['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                    $takeout_sale_amount += $val['order_amount'];
                }
                if ($val['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                    $shop_order += 1;
                }
                if ($val['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                    $takeout_order += 1;
                }
            }

            $lists[] = [
                'sale_amount' => round($sale_amount,2),
                'all_order' => round($all_order,2),
                'shop_sale_amount' => round($shop_sale_amount,2),
                'takeout_sale_amount' => round($takeout_sale_amount,2),
                'shop_order' => round($shop_order,2),
                'takeout_order' => round($takeout_order,2),
                'refund_amount' => round($refund_amount,2),
                'time' => date('Y-m-d',$i),
            ];
        }
        $count = count($lists);
        krsort($lists);
        $page_no = $get['page'] * $get['limit'] - $get['limit'];
        $lists = array_slice($lists, $page_no, $get['limit']);

        return ['count'=>$count, 'lists'=>$lists];
    }

    /**
     * @notes 导出列表
     * @param $get
     * @param $shop_id
     * @return array
     * @author ljj
     * @date 2021/9/28 10:39 上午
     */
    public static function exportFile($get,$shop_id)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['shop_id', '=', $shop_id];
        $where[] = ['pay_status', '=', PayEnum::ISPAID];
        if (!empty($get['start_time']) and $get['start_time']) {
            $where[] = ['create_time', '>=', strtotime($get['start_time'])];
        }

        if (!empty($get['end_time']) and $get['end_time']) {
            $where[] = ['create_time', '<=', strtotime($get['end_time'])];
        }

        $lists = Order::where($where)
            ->group('FROM_UNIXTIME(create_time,"%Y-%m-%d")')
            ->order('create_time','desc')
            ->column('FROM_UNIXTIME(create_time,"%Y-%m-%d") as time,sum(order_amount) as sale_amount,IF(order_type=1,sum(order_amount),0.00) as shop_sale_amount,IF(order_type=2,sum(order_amount),0.00) as takeout_sale_amount,IFNULL(sum(refund_amount),0.00) as refund_amount,count(id) as all_order,IF(order_type=1,count(id),0) as shop_order,IF(order_type=2,count(id),0) as takeout_order');

        $exportTitle = ['日期', '营业额', '到店营业额', '外卖营业额', '退款金额', '全部订单', '到店订单', '外卖订单'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            $exportData[] = [$item['time'], $item['sale_amount'], $item['shop_sale_amount'], $item['takeout_sale_amount'], $item['refund_amount'], $item['all_order'], $item['shop_order'], $item['takeout_order']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'财务查询'.date('Y-m-d H:i:s',time())];
    }

}