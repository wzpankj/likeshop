<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\api\validate;

use app\common\basics\Validate;
use app\common\logic\SmsLogic;
use app\common\model\Client_;
use app\common\model\user\User;

/**
 * 修改用户手机号验证器
 * Class ChangeMobileValidate
 * @package app\api\validate
 */
class ChangeMobileValidate extends Validate
{
    protected $rule = [
        'mobile' => 'require|mobile',
        'new_mobile' => 'require|mobile|checkMobile',
    ];

    protected $message = [
        'mobile.require' => '参数缺失',
        'mobile.mobile' => '请填写正确的手机号',
        'new_mobile.mobile' => '请填写正确的手机号',
        'new_mobile.require' => '请填写手机号'
    ];


    public function sceneBinding()
    {
        $this->only(['new_mobile']);
    }


    //检查新手机号是否已存在
    protected function checkMobile($value, $rule, $data)
    {
        $user = User::where([
            ['mobile', '=', $value],
            ['id', '<>', $data['user_id']]
        ])->find();

        if ($user) {
            return '此手机号已被使用';
        }

//        if (!isset($data['code'])) {
//            return '请填写验证码';
//        }

//        $mobile = $data['new_mobile'];
//        if (isset($data['action']) && 'change' == $data['action']) {
//            $mobile = $data['mobile'];
//        }

//        $res = SmsLogic::check($data['message_key'], $mobile, $data['code']);
//        if (false === $res) {
//            return SmsLogic::getError();
//        }
        return true;
    }
}