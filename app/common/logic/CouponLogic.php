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
namespace app\common\logic;
use app\common\enum\CouponEnum;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;

/**
 * 优惠券逻辑层
 * Class CouponLogic
 * @package app\common\logic
 */
class CouponLogic {

    /**
     * @notes 获取优惠券信息
     * @param $coupon
     * @return mixed
     * @author cjhao
     * @date 2022/1/13 15:35
     */
    public static function getCouponInfo($coupon){

        //优惠券金额
        $discount_money_desc = $coupon['condition_type'] == CouponEnum::CONDITION_TYPE_NOT ? '无门槛' : '满足'.$coupon['condition_money'].'元可用';
        $get_tips_desc = '';

        $get_num = CouponList::where(['coupon_id'=>$coupon['id']])
            ->whereDay('create_time','today')
            ->count('id');

        switch ($coupon['get_num_type']){
            case CouponEnum::GET_NUM_TYPE_LIMIT:
                $get_tips_desc = '限制领取'.$coupon['get_num'].'次';
                break;
            case CouponEnum::GET_NUM_TYPE_DAY:
                $get_tips_desc = '每天限制领取'.$coupon['get_num'].'次';
                break;

        }
        //用券时间
        $use_time_desc = '';
        if(1 == $coupon['use_time_type']){

            $use_time_desc = date('Y-m-d H:i:s',$coupon['use_time_start']) .'~'. date('Y-m-d H:i:s',$coupon['use_time_end']);

        }else{

            $use_time_desc = '领取内'.$coupon['use_time'].'天';

        }
        //发放总量
        $send_total_desc = '不限';
        $get_tips_desc = $get_num.'/不限';
        if(2 == $coupon['send_total_type']){
            $surplus = $coupon['send_total'] - $get_num;
            $get_tips_desc = $get_num.'/剩余'.$surplus.'张';
            $send_total_desc = '限量'.$coupon['send_total'].'张';
        }
        //发放时间
        $coupon['send_time_desc'] = date('Y-m-d H:i:s',$coupon['send_time_start']) .'~'. date('Y-m-d H:i:s',$coupon['send_time_end']) ;
        //用券时间
        $coupon['use_time_desc'] = $use_time_desc;
        //优惠金额
        $coupon['discount_money_desc'] = $discount_money_desc;
        //已领取、剩余
        $coupon['get_num_desc'] = $get_num;
//        $coupon['get_tips_desc'] = $get_num.'/'. $get_tips_desc;
        $coupon['get_tips_desc'] = $get_tips_desc;
        //已使用
//        $coupon['use_coupon_num'] = $use_coupon_num;
        //使用场景
        $coupon['use_goods_type_desc'] = CouponEnum::getUseGoods($coupon['use_goods_type']);
        //发放总量
        $coupon['send_total_desc'] = $send_total_desc;

        //状态
        $coupon['status_desc'] = CouponEnum::getCouponStatus($coupon['status']);

        return $coupon;


    }

    /**
     * @notes 更新过期优惠券
     * @author cjhao
     * @date 2022/1/24 18:41
     */
    public static function updateOverCoupon(){
        Coupon::where(['use_time_type'=>CouponEnum::USE_TIME_TYPE_FIXED])
            ->where('use_time_end','<=',time())
            ->update(['update_time'=>time(),'status'=>CouponEnum::COUPON_STATUS_END]);
    }



}