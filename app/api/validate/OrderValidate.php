<?php

namespace app\api\validate;
use app\common\basics\Validate;
use app\common\enum\OrderEnum;


/**
 * 订单验证器
 * Class OrderValidate
 * @package app\api\validate
 */
class OrderValidate extends Validate
{

    protected $rule = [
        'shop_id'       => 'require',
        'order_type'    => 'require|in:'.OrderEnum::ORDER_TYPE_YOUSELF_TAKE.','.OrderEnum::ORDER_TYPE_TAKE_AWAY,
        'dining_type'   => 'requireIf:order_type,'.OrderEnum::ORDER_TYPE_YOUSELF_TAKE.'|in:'.OrderEnum::DINING_TYPE_EAT_IN.','.OrderEnum::DINING_TYPE_TAKE_EAT,
//        'address_id'    => 'requireIf:order_type,'.OrderEnum::ORDER_TYPE_TAKE_AWAY,
        'mobile'        => 'mobile',
        'order_remarks' => 'max:128',
        'jifen_dikou' => 'max:128',
    ];

    protected $message = [
        'shop_id.require'       => '请选择门店',
        'order_type.require'    => '请选择订单类型',
        'order_type.in'         => '订单类型错误',
        'dining_type.requireIf' => '自取订单请选择就餐方式',
        'dining_type.in'        => '自取订单类型错误',
        'address_id.requireIf'  => '请选择收货地址',
        'order_remarks.max'     => '备注不能超过128字符',
        'mobile.mobile'         => '手机号码格式错误',

    ];

    public function sceneSumbitOrder()
    {
        return $this->only(['shop_id','order_type','dining_type','address_id','mobile','order_remarks','jifen_dikou']);
    }


}
