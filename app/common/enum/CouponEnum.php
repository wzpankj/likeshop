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

use function JmesPath\search;

class CouponEnum{

    // 使用条件
    const CONDITION_TYPE_NOT  = 1;  //无门槛
    const CONDITION_TYPE_FULL = 2;  //需订单满足金额

    // 发放数量限制
    const SEND_TOTAL_TYPE_NOT   = 1; // 无限数量
    const SEND_TOTAL_TYPE_FIXED = 2; // 固定数量

    // 用券时间
    const USE_TIME_TYPE_FIXED    = 1; //固定时间
    const USE_TIME_TYPE_TODAY    = 2; //当日起多少天内
    const USE_TIME_TYPE_TOMORROW = 3; //次日起多少天内

    // 领取方式
    const GET_TYPE_USER  = 1; //买家领取
//    const GET_TYPE_STORE = 2; //商家发放

    // 领取数量限制
    const GET_NUM_TYPE_NOT   = 1; //不限制
    const GET_NUM_TYPE_LIMIT = 2; //限制张数
    const GET_NUM_TYPE_DAY   = 3; //每天限制张数

    // 允许使用商品
    const USE_GOODS_TYPE_NOT   = 1; //不限制
    const USE_GOODS_TYPE_ALLOW = 2; //允许商品
    const USE_GOODS_TYPE_BAN   = 3; //禁止商品
    //优惠券状态
    const COUPON_STATUS_NOT     = 1; //未开始
    const COUPON_STATUS_CONDUCT = 2; //进行中
    const COUPON_STATUS_END     = 3; //已结束

    /**
     * @notes 领取类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/1/18 18:07
     */
    public static function getCouponType($from = true){
        $desc = [
            self::GET_TYPE_USER         => '买家领取',
            self::GET_TYPE_STORE        => '商家发放',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取优惠券可使用商品
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/1/18 17:47
     */
    public static function getUseGoods($from = true){
        $desc = [
            self::USE_GOODS_TYPE_NOT         => '全店通用',
            self::USE_GOODS_TYPE_ALLOW       => '部分商品可用',
            self::USE_GOODS_TYPE_BAN         => '部分商品不可用',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取优惠券类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2022/1/18 17:46
     */
    public static function getCouponStatus($from = true){
        $desc = [
            self::COUPON_STATUS_NOT         => '未开始',
            self::COUPON_STATUS_CONDUCT     => '进行中',
            self::COUPON_STATUS_END         => '已结束',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }


}