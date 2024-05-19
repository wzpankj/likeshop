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
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\model\order\Order;

/**
 * 订单概况逻辑层
 * Class ProfileLogic
 * @package app\shop\logic\order
 */
class ProfileLogic extends Logic
{
    /**
     * @notes 获取订单信息
     * @return array
     * @author ljj
     * @date 2021/9/11 10:43 上午
     */
    public static function getOrderInfo($admin_id)
    {
        $order = new Order;
        $where[] = ['del', '=', 0];
        $where[] = ['shop_id', '=', $admin_id];
        $where[] = ['pay_status', '=', PayEnum::ISPAID];
        return [
            'order_sum' => $order->where($where)->where('order_status',OrderEnum::ORDER_STATUS_COMPLETE)->count(), //总订单
            'shop_order_sum' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_YOUSELF_TAKE])->count(), //到店订单
            'takeout_order_sum' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_TAKE_AWAY])->count(), //外卖订单

            'day_shop_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_YOUSELF_TAKE])->whereDay('create_time')->count(), //今日到店订单
            'afoot_shop_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_YOUSELF_TAKE,'order_status'=>OrderEnum::ORDER_STATUS_DELIVERY])->count(), //制作中订单
            'wait_shop_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_YOUSELF_TAKE,'order_status'=>OrderEnum::ORDER_STATUS_GOODS])->count(), //待取餐订单

            'day_takeout_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_TAKE_AWAY])->whereDay('create_time')->count(), //今日外卖订单
            'afoot_takeout_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_TAKE_AWAY,'order_status'=>OrderEnum::ORDER_STATUS_DELIVERY])->count(), //制作中订单
            'delivery_takeout_order' => $order->where($where)->where(['order_type'=>OrderEnum::ORDER_TYPE_TAKE_AWAY,'order_status'=>OrderEnum::ORDER_STATUS_GOODS])->count(), //配送中订单
        ];
    }
}