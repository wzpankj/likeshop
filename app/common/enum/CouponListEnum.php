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
namespace app\common\enum;
class CouponListEnum{
    const STATUS_NOT_USE = 0;
    const STATUS_USE     = 1;
    const STATUS_OVERDUE = 2;


    /**
     * @notes 获取优惠券状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/1/14 11:58
     */
    public static function getStatusDesc($from = true){
        $desc = [
            self::STATUS_NOT_USE    => '未使用',
            self::STATUS_USE        => '已使用',
            self::STATUS_OVERDUE    => '已过期',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }
}