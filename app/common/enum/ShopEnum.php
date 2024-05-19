<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\enum;


/**
 * 门店枚举
 * Class ShopEnum
 * @package app\common\enum
 */
class ShopEnum
{

    const PRICING_POLICY_HQ     = 1;    //总部定价
    const PRICING_POLICY_SHOP   = 2;    //门店定价

    const DELIVERY_PICK_UP      = 1;
    const DELIVERY_TAKE_OUT     = 2;

    /**
     * @notes 定价策略
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/9/3 11:08
     */
    public static function getPricingPriceDesc($from = true)
    {
        $desc = [
            self::PRICING_POLICY_HQ     => '总部定价',
            self::PRICING_POLICY_SHOP   => '门店定价',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 配送方式
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/10/9 14:39
     */
    public static function getDeliveryTypeDesc($from = true)
    {
        $desc = [
            self::DELIVERY_PICK_UP          => '自提',
            self::DELIVERY_TAKE_OUT         => '外卖',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

}