<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\api\validate;
use app\common\{enum\PayEnum, model\DevPay, enum\DevPayEnum, basics\Validate};

/**
 * 发起支付验证器
 * Class UnifiedpayVlidate
 * @package app\api\validate
 */
class UnifiedpayVlidate extends Validate
{
    protected $rule = [
        'order_id'      => 'require',
        'from'          => 'require',
        'pay_way'       => 'require|checkPayWay',
    ];
    protected $message = [
        'order_id.require'  => '请选择订单',
        'from.require'      => '请选择订单类型',
        'pay_way.require'   => '请选择支付方式',
    ];

    protected function checkPayWay($value,$rule,$data)
    {
        $code = 'balance';
        if( PayEnum::WECHAT_PAY == $value){
            $code = 'wechat';
        }
        $pay_way = DevPay::where(['code'=>$code])
                ->find();
        if(empty($pay_way)){
            return '支付方式错误';
        }
        if(DevPayEnum::STATUS_CLOSE == $pay_way->status){
            return '该支付类型暂不支持';
        }
        return true;

    }

}