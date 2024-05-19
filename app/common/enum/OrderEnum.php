<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------


namespace app\common\enum;

/**
 * 订单相关 枚举类型
 * Class OrderEnum
 * @Author FZR
 * @package app\common\enum
 */
class OrderEnum
{

    //订单状态
    const ORDER_STATUS_NO_PAID  = 0;//待付款
    const ORDER_STATUS_DELIVERY = 1;//制作中
    const ORDER_STATUS_GOODS    = 2;//待取餐/配送中
    const ORDER_STATUS_COMPLETE = 3;//已完成
    const ORDER_STATUS_DOWN     = 4;//已关闭

    //就餐方式
    const DINING_TYPE_EAT_IN    = 1;    //堂食
    const DINING_TYPE_TAKE_EAT  = 2;    //打包

    //订单类型
    const ORDER_TYPE_YOUSELF_TAKE   = 1;    //到店订单
    const ORDER_TYPE_TAKE_AWAY      = 2;    //外卖订单

    //支付状态
    const PAY_STATUS_NO     = 0;
    const PAY_STATUS_PAY    = 1;

    //支付方式pay_way
    const PAY_WAY_WECHAT    = 1;//微信支付
    const PAY_WAY_BALANCE   = 2;//余额支付

    //退款状态
    const NO_REFUND = 0;//未退款
    const REFUND = 1;//退款成功
    const REFUND_FAIL = 2;//退款失败

    //是否预约单
    const APPOINT_NO      = 0;
    const APPOINT_YES     = 1;

    /**
     * @notes 自提订单状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/9/8 10:52
     */
    public static function getPickUpOrderStatus($from = true)
    {
        $desc = [
            self::ORDER_STATUS_NO_PAID  => '待付款',
            self::ORDER_STATUS_DELIVERY => '制作中',
            self::ORDER_STATUS_GOODS    => '待取餐',
            self::ORDER_STATUS_COMPLETE => '已完成',
            self::ORDER_STATUS_DOWN     => '已关闭'
        ];

        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 外卖订单
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/10/15 16:41
     */
    public static function getTakeOutOrderStatus($from = true){
        $desc = [
            self::ORDER_STATUS_NO_PAID  => '待付款',
            self::ORDER_STATUS_DELIVERY => '制作中',
            self::ORDER_STATUS_GOODS    => '配送中',
            self::ORDER_STATUS_COMPLETE => '已完成',
            self::ORDER_STATUS_DOWN     => '已关闭'
        ];

        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 退款状态
     * @param $type
     * @return string|string[]
     * @author ljj
     * @date 2021/9/18 2:57 下午
     */
    public static function getRefundStatus($type)
    {
        $data = [
            self::NO_REFUND => '未退款',
            self::REFUND => '退款成功',
            self::REFUND_FAIL => '退款失败',
        ];

        if ($type === true) {
            return $data;
        }
        return $data[$type] ?? '未知';
    }

    /**
     * @notes 就餐方式
     * @param bool $from
     * @author cjhao
     * @date 2021/9/8 10:51
     */
    public static function getDiningTypeDesc($from = true)
    {
        $desc = [
            self::DINING_TYPE_EAT_IN    => '店内就餐',
            self::DINING_TYPE_TAKE_EAT  => '打包带走',
        ];
        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';

    }

    /**
     * @Notes: 支付状态
     * @Author: cjhao
     * @param bool $type
     * @return array|mixed|string
     */
    public static function getPayStatusDesc($from = true)
    {
        $desc = [
            self::PAY_STATUS_NO     => '待支付',
            self::PAY_STATUS_PAY    => '已支付',
        ];

        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取订单类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/9/8 10:58
     */
    public static function getOrderTypeDesc($from = true)
    {
        $desc = [
            self::ORDER_TYPE_YOUSELF_TAKE   => '到店订单',
            self::ORDER_TYPE_TAKE_AWAY      => '外卖订单',
        ];

        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取支付方式
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/9/18 14:26
     */
    public static function getPayWatStatusDesc($from = true){
        $desc = [
            self::PAY_WAY_WECHAT    => '微信支付',
            self::PAY_WAY_BALANCE   => '余额支付',
        ];

        if (true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }
}