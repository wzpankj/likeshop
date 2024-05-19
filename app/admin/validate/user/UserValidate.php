<?php
namespace app\admin\validate\user;

use app\common\basics\Validate;

/**
 * 用户验证类
 * Class UserValidate
 * @package app\admin\validate\user
 */
class UserValidate extends Validate
{
    protected $rule = [
        'id'       => 'require',
        'nickname'      => 'require|max:32',
        'avatar'        => 'require',
        'mobile'        => 'mobile',
        'sex'           => 'in:0,1,2',
        'money'         => 'require|egt:0',
        'money_remark'  => 'require|max:100'
    ];

    protected $message = [
        'id.require'            => '请选择调整的用户',
        'nickname.require'      => '请填写用户昵称',
        'nickname.max'          => '用户昵称不能超过32个字符',
        'avatar.require'        => '请选择用户头像',
        'mobile.mobile'         => '手机号码格式错误',
        'money.require'         => '请输入调整金额',
        'money.egt'             => '调整金额必须大于0',
        'money_remark.require'  => '请输入余额调整备注',
        'money_remark.max'      => '备注不能超过100个字符',
    ];

    public function sceneInfo()
    {
        return $this->only(['id']);
    }


    public function sceneAccount()
    {
        return $this->only(['id', 'money', 'money_remark']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','nickname','avatar']);
    }
}