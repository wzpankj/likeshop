<?php
namespace app\api\validate;

use think\Validate;

class WechatMobileValidate extends Validate
{
    protected $rule = [
        'code' => 'require',
        'encrypted_data' => 'require',
        'iv' => 'require',
    ];
}