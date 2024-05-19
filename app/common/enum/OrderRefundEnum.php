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
 * 退款订单相关 枚举类型
 * Class OrderRefundEnum
 * @Author ISH
 * @package app\common\enum
 */
class OrderRefundEnum
{
    //退款状态refund_status
    const REFUND_STATUS_ING = 0;//退款中
    const REFUND_STATUS_COMPLETE = 1;//退款完成
    const REFUND_STATUS_FAIL = 2;//退款失败
    const REFUND_STATUS_ABNORMAL = 3;//退款异常

    //退款类型
    const REFUND_TYPE_TOTAL = 1;//整单退款
//    const REFUND_TYPE_NOT_FARE = 2;//整单退款（不含运费）
//    const REFUND_TYPE_GOODS = 3;//商品退款
//    const REFUND_TYPE_FARE = 4;//运费退款
    const REFUND_TYPE_OTHER = 5;//其他退款

    //退款方式
    const REFUND_WAY_ORIGINAL = 0;//原路退款
//    const REFUND_WAY_BALANCE = 1;//退款到余额
//    const REFUND_WAY_CASH = 2;//现金退款

    /**
     * @notes 订单退款表退款状态
     * @param $type
     * @return string|string[]
     * @author ljj
     * @date 2021/9/18 3:04 下午
     */
    public static function getRefundStatus($type)
    {
        $data = [
            self::REFUND_STATUS_ING => '退款中',
            self::REFUND_STATUS_COMPLETE => '退款成功',
            self::REFUND_STATUS_FAIL => '退款失败',
            self::REFUND_STATUS_ABNORMAL => '退款异常',
        ];

        if ($type === true) {
            return $data;
        }
        return $data[$type] ?? '未知';
    }

    /**
     * @notes 退款类型
     * @param $type
     * @return string|string[]
     * @author ljj
     * @date 2021/9/22 2:18 下午
     */
    public static function getRefundType($type)
    {
        $data = [
            self::REFUND_TYPE_TOTAL => '整单退款',
//            self::REFUND_TYPE_NOT_FARE => '整单退款（不含运费）',
//            self::REFUND_TYPE_GOODS => '商品退款',
//            self::REFUND_TYPE_FARE => '运费退款',
            self::REFUND_TYPE_OTHER => '其他退款',
        ];

        if ($type === true) {
            return $data;
        }
        return $data[$type] ?? '未知';
    }

    /**
     * @notes 退款方式
     * @param $type
     * @return string|string[]
     * @author ljj
     * @date 2021/9/22 2:21 下午
     */
    public static function getRefundWay($type)
    {
        $data = [
            self::REFUND_WAY_ORIGINAL => '原路退款',
//            self::REFUND_WAY_BALANCE => '退款到余额',
//            self::REFUND_WAY_CASH => '现金退款',
        ];

        if ($type === true) {
            return $data;
        }
        return $data[$type] ?? '未知';
    }
   
}