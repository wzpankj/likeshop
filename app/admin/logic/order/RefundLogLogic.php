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
use app\common\enum\OrderRefundEnum;
use app\common\model\order\Order;
use app\common\model\order\OrderRefund;

/**
 * 退款记录控制层
 * Class RefundLogLogic
 * @package app\shop\logic\order
 */
class RefundLogLogic extends Logic
{
    /**
     * @notes 退款记录统计
     * @param $shop_id
     * @return int[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/22 2:31 下午
     */
    public static function statistics($get)
    {
        $where = [];

        //退款信息
        if (isset($get['refund_sn']) && $get['refund_sn'] != '') {
            $where[] = ['or.refund_sn', 'like', '%'.$get['refund_sn'].'%'];
        }
        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //退款商品
        if (isset($get['goods_info']) && $get['goods_info'] != '') {
            $where[] = ['og.goods_name|g.code', 'like', '%'.$get['goods_info'].'%'];
        }
        //退款类型
        if (isset($get['refund_type']) && $get['refund_type'] != '') {
            $where[] = ['or.refund_type', '=', $get['refund_type']];
        }
        //退款路径
        if (isset($get['refund_way']) && $get['refund_way'] != '') {
            $where[] = ['or.refund_way', '=', $get['refund_way']];
        }
        //退款时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['or.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['or.create_time', '<=', strtotime($get['end_time'])];
        }

        $result = Order::alias('o')
            ->field('or.refund_status')
            ->join('order_refund or', 'o.id = or.order_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->join('shop s', 's.id = o.shop_id')
            ->where($where)
            ->select()->toArray();

        $all = 0;// 全部
        $wait_refund = 0;// 退款中
        $refund_success = 0;// 退款成功
        $refund_fail = 0;// 退款失败

        foreach ($result as $val) {
            $all += 1;
            if ($val['refund_status'] == OrderRefundEnum::REFUND_STATUS_ING) {
                $wait_refund += 1;
            }
            if ($val['refund_status'] == OrderRefundEnum::REFUND_STATUS_COMPLETE) {
                $refund_success += 1;
            }
            if ($val['refund_status'] == OrderRefundEnum::REFUND_STATUS_FAIL) {
                $refund_fail += 1;
            }
        }

        return [
            'all' => $all,
            'wait_refund' => $wait_refund,
            'refund_success' => $refund_success,
            'refund_fail' => $refund_fail,
        ];
    }

    /**
     * @notes 退款记录列表
     * @param array $get
     * @param $shop_id
     * @return array
     * @author ljj
     * @date 2021/9/22 4:32 下午
     */
    public static function lists($get = [])
    {
        $where = [];

        //退款信息
        if (isset($get['refund_sn']) && $get['refund_sn'] != '') {
            $where[] = ['or.refund_sn', 'like', '%'.$get['refund_sn'].'%'];
        }
        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //退款商品
        if (isset($get['goods_info']) && $get['goods_info'] != '') {
            $where[] = ['og.goods_name|g.code', 'like', '%'.$get['goods_info'].'%'];
        }
        //退款类型
        if (isset($get['refund_type']) && $get['refund_type'] != '') {
            $where[] = ['or.refund_type', '=', $get['refund_type']];
        }
        //退款路径
        if (isset($get['refund_way']) && $get['refund_way'] != '') {
            $where[] = ['or.refund_way', '=', $get['refund_way']];
        }
        //退款时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['or.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['or.create_time', '<=', strtotime($get['end_time'])];
        }

        //订单状态
        $type = $get['type'] ?: 0;
        switch ($type) {
            case 1:      //退款中
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_ING];
                break;
            case 2:     //退款成功
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_COMPLETE];
                break;
            case 3:     //退款失败
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_FAIL];
                break;
            default:
                break;
        }

        $field = 'o.id,or.id as order_refund_id,or.refund_sn,or.refund_status,or.create_time as refund_time,o.order_sn,o.create_time as order_time,or.refund_type,or.refund_way,or.refund_amount,or.arrival_time,or.transaction_id,s.shop_sn,s.name as shop_name';

        $count = Order::alias('o')
            ->field($field)
            ->join('order_refund or', 'o.id = or.order_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods'])
            ->where($where)
            ->count();

        $lists = Order::alias('o')
            ->field($field)
            ->join('order_refund or', 'o.id = or.order_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }])
            ->where($where)
            ->page($get['page'], $get['limit'])
            ->order('or.id desc')
            ->select()->toArray();

        foreach ($lists as &$list) {
            $list['order_time'] = date('Y-m-d H:i:s',$list['order_time']);
            $list['refund_status_desc'] = OrderRefundEnum::getRefundStatus($list['refund_status']);
            $list['refund_type_desc'] = OrderRefundEnum::getRefundType($list['refund_type']);
            $list['refund_way_desc'] = OrderRefundEnum::getRefundWay($list['refund_way']);
            $list['arrival_time'] = $list['arrival_time'] ? date('Y-m-d H:i:s',$list['arrival_time']) :'-';
            $list['transaction_id'] = $list['transaction_id'] ?? '-';

            foreach ($list['order_goods'] as &$val) {
                if (!empty($val['goods_snap']['material_name'])) {
                    $val['spec_value'] = $val['goods_snap']['spec_value_str'].','.implode(',',$val['goods_snap']['material_name']);
                }else {
                    $val['spec_value'] = $val['goods_snap']['spec_value_str'];
                }

                unset($val['goods_snap']);
            }
        }

        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 导出列表
     * @param $get
     * @param $shop_id
     * @return array
     * @author ljj
     * @date 2021/9/22 4:40 下午
     */
    public static function exportFile($get)
    {
        $where = [];

        //退款信息
        if (isset($get['refund_sn']) && $get['refund_sn'] != '') {
            $where[] = ['or.refund_sn', 'like', '%'.$get['refund_sn'].'%'];
        }
        //订单信息
        if (isset($get['order_sn']) && $get['order_sn'] != '') {
            $where[] = ['o.order_sn', 'like', '%'.$get['order_sn'].'%'];
        }
        //退款商品
        if (isset($get['goods_info']) && $get['goods_info'] != '') {
            $where[] = ['og.goods_name|g.code', 'like', '%'.$get['goods_info'].'%'];
        }
        //退款类型
        if (isset($get['refund_type']) && $get['refund_type'] != '') {
            $where[] = ['or.refund_type', '=', $get['refund_type']];
        }
        //退款路径
        if (isset($get['refund_way']) && $get['refund_way'] != '') {
            $where[] = ['or.refund_way', '=', $get['refund_way']];
        }
        //退款时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['or.create_time', '>=', strtotime($get['start_time'])];
        }
        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['or.create_time', '<=', strtotime($get['end_time'])];
        }

        //订单状态
        $type = $get['type'] ?: 0;
        switch ($type) {
            case 1:      //退款中
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_ING];
                break;
            case 2:     //退款成功
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_COMPLETE];
                break;
            case 3:     //退款失败
                $where[] = ['or.refund_status', '=', OrderRefundEnum::REFUND_STATUS_FAIL];
                break;
            default:
                break;
        }

        $lists = Order::alias('o')
            ->field('or.id,or.refund_sn,or.refund_status,or.create_time as refund_time,o.order_sn,o.create_time as order_time,or.refund_type,or.refund_way,or.refund_amount,or.arrival_time,or.transaction_id,s.shop_sn,s.name as shop_name')
            ->join('order_refund or', 'o.id = or.order_id')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->join('shop s', 's.id = o.shop_id')
            ->with(['order_goods' => function($query){
                $query->field('order_id,goods_name,goods_num,goods_price,goods_snap')->json(['goods_snap'],true);
            }])
            ->where($where)
            ->order('o.id desc')
            ->select()->toArray();

        $exportTitle = ['ID', '门店信息', '退款编号', '退款状态', '退款时间', '订单编号', '下单时间', '商品信息', '退款类型', '退款路径', '退款金额', '到账时间', '退款流水账号'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            $item['order_time'] = date('Y-m-d H:i:s',$item['order_time']);
            $item['refund_status_desc'] = OrderRefundEnum::getRefundStatus($item['refund_status']);
            $item['refund_type_desc'] = OrderRefundEnum::getRefundType($item['refund_type']);
            $item['refund_way_desc'] = OrderRefundEnum::getRefundWay($item['refund_way']);
            $item['arrival_time'] = $item['arrival_time'] ? date('Y-m-d H:i:s',$item['arrival_time']) :'-';
            $item['transaction_id'] = $item['transaction_id'] ?? '-';

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

            $exportData[] = [$item['id'],$item['shop_sn'].'-'.$item['shop_name'], $item['refund_sn'], $item['refund_status_desc'], $item['refund_time'], $item['order_sn'], $item['order_time'], $goods_info, $item['refund_type_desc'], $item['refund_way_desc'], $item['refund_amount'], $item['arrival_time'], $item['transaction_id']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'退款记录列表'.date('Y-m-d H:i:s',time())];
    }
}