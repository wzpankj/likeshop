<?php
namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 更新用户信息验证器
 * Class UpdateUserValidate
 * @package app\api\validate
 */
class UpdateUserValidate extends Validate
{
    protected $rule = [
        'avatar' => 'require',
        'nickname' => 'require',
        'mobile' => 'mobile',
        'sex' => 'require|in:0,1,2',
    ];

    protected $message = [
        'avatar.require' => '头像不能为空',
        'nickname.require' => '用户昵称不能为空',
        'sex.require' => '性别不能为空',
    ];


    public function sceneSet()
    {
        $this->only(['avatar', 'nickname', 'mobile', 'sex']);
    }

}