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
namespace app\admin\validate;
use think\Validate;

class RechargeTemplateValidate extends Validate{
    protected $rule = [
        'money'          => 'require|gt:0',
        'give_money'     => 'gt:0',
        'sort'         => 'integer|egt:0',
    ];
    protected $message = [
        'money.require'     => '请输入充值金额',
        'money.gt'          => '充值金额须为大于0',
        'give_money.gt'     => '赠送金额须为大于0',
        'sort.integer'    => '排序值须为整数',
        'sort.egt'        => '排序值须大于或等于0',
    ];
}
