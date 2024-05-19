<?php
namespace app\common\enum;

class OrderLogEnum
{
    //操作人类型
    const TYPE_USER = 0;//会员
    const TYPE_SHOP = 1;//门店
    const TYPE_SYSTEM   = 2;//系统

    //订单动作
    const USER_ADD_ORDER = 101;//提交订单
    const USER_CANCEL_ORDER = 102;//取消订单
    const USER_DEL_ORDER = 103;//删除订单
    const USER_CONFIRM_ORDER = 104;//确认收货
    const USER_PAID_ORDER = 105;//支付订单

    const SHOP_CANCEL_ORDER = 201;//商家取消订单
    const SHOP_DEL_ORDER = 202;//商家删除订单
    const SHOP_NOTICE_ORDER = 203;//商家通知取餐
    const SHOP_CONFIRM_ORDER = 204;//商家确认取餐
    const SHOP_REMARKS_ORDER = 205;//商家备注
    const SHOP_REFUND_ORDER = 206;//商家退款

    const SYSTEM_CANCEL_ORDER   = 301;//系统取消订单
    const SYSTEM_CONFIRM_ORDER  = 302;//系统确认订单

    //订单动作明细
    public static function getLogDesc($log)
    {
        $desc = [
            OrderLogEnum::USER_ADD_ORDER => '会员提交订单',
            OrderLogEnum::USER_CANCEL_ORDER => '会员取消订单',
            OrderLogEnum::USER_DEL_ORDER => '会员删除订单',
            OrderLogEnum::USER_CONFIRM_ORDER => '会员确认收货',
            OrderLogEnum::USER_PAID_ORDER => '会员支付订单',

            OrderLogEnum::SHOP_CANCEL_ORDER => '商家取消订单',
            OrderLogEnum::SHOP_DEL_ORDER => '商家删除订单',
            OrderLogEnum::SHOP_NOTICE_ORDER => '商家通知取餐',
            OrderLogEnum::SHOP_CONFIRM_ORDER => '商家确认取餐',
            OrderLogEnum::SHOP_REMARKS_ORDER => '商家备注',
            OrderLogEnum::SHOP_REFUND_ORDER => '商家退款',
        ];

        if ($log === true) {
            return $desc;
        }

        return isset($desc[$log]) ? $desc[$log] : $log;
    }


}

