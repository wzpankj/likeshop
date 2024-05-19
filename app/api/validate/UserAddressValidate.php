<?php

namespace app\api\validate;


use app\common\basics\Validate;

/**
 * 用户地址验证器
 * Class UserAddressValidate
 * @package app\api\validate
 */
class UserAddressValidate extends Validate
{
    protected $rule = [
        'id'            => 'require|integer',
        'contact'       => 'require',
        'telephone'     => 'require|mobile',
        'address'       => 'require',
        'is_default'    => 'require',
        'longitude'       => 'require',
        'latitude'    => 'require',
    ];

    protected $message = [
        'id.require'            => 'id不能为空',
        'id.integer'            => 'id参数错误',
        'contact.require'       => '收货人不能为空',
        'telephone.require'     => '联系方式不能为空',
        'telephone.mobile'      => '非有效手机号',
        'address.require'       => '详细地址不能为空',
        'is_default.require'    => '是否默认不能为空',
        'longitude.require'     => '经度不能为空',
        'latitude.require'      => '纬度不能为空',
    ];

    protected $scene = [
        'add' => ['contact','telephone','is_default','address','longitude','latitude'],
        'set'=>['id'],
        'detail'=>['id'],
        'edit'=>['id','contact','telephone','is_default','address','longitude','latitude'],
        'del'=>['id'],
    ];


    /**
     * 获取省市区id
     */
    public function sceneHandleRegion()
    {
        return $this->only(['province','city','district'])
            ->append('province','require')
            ->append('city','require')
            ->append('district','require');
    }
}
