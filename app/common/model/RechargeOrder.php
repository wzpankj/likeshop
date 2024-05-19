<?php
namespace app\common\model;

use app\common\basics\Models;
use app\common\enum\RechargeOrderEnum;

class RechargeOrder extends Models
{
    //支付方式
    public static function getPayWay($status = true)
    {
        $desc = [
            RechargeOrderEnum::WECHAT_PAY => '微信支付',
            RechargeOrderEnum::ALI_PAY => '支付宝支付',
        ];
        if ($status === true) {
            return $desc;
        }
        return $desc[$status] ?? '未知';
    }

    //支付状态
    public static function getPayStatus($status = true)
    {
        $desc = [
            RechargeOrderEnum::PAY_STATUS_NO_PAID => '待支付',
            RechargeOrderEnum::PAY_STATUS_PAID => '已支付',
        ];
        if ($status === true) {
            return $desc;
        }
        return $desc[$status] ?? '未知';
    }

    //支付状态
    public function getPayStatusAttr($value, $data)
    {
        return self::getPayStatus($data['pay_status']);
    }

    //支付方式
    public function getPayWayAttr($value, $data)
    {
        return self::getPayWay($data['pay_way']);
    }

    //支付时间
    public function getPayTimeAttr($value, $data)
    {
        if(!$data['pay_time']){
            return '';
        }
        return date('Y-m-d H:i:s',$data['pay_time']);
    }
}