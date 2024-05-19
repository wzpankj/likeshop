<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\logic;

use app\common\enum\CouponListEnum;
use app\common\enum\OrderLogEnum;
use app\common\model\marketing\CouponList;
use app\common\model\order\OrderLog;
use app\common\model\user\User;
use app\common\logic\IntegralLogic;

/**
 * 订单记录日志
 * Class OrderLogLogic
 * @package app\common\logic
 */
class OrderLogLogic
{
    /**
     * @notes 订单日志
     * @param $type
     * @param $channel
     * @param $order_id
     * @param $handle_id
     * @param $shop_id
     * @param string $content
     * @author cjhao
     * @date 2021/9/18 18:35
     */
    public static function record($type, $channel, $order_id, $handle_id,$shop_id,$content = '')
    {

        $order_log = new OrderLog();
        $order_log->type         = $type;
        $order_log->order_id     = $order_id;
        $order_log->channel      = $channel;
        $order_log->handle_id    = $handle_id;
        $order_log->shop_id      = $shop_id;
        $order_log->content      = $content ?: OrderLogEnum::getLogDesc($channel);
        $order_log->create_time  = time();
        $order_log->save();
    }

    /**
     * @notes 取消订单的后续操作(退回优惠券等)
     * @param $order
     * @author cjhao
     * @date 2022/1/20 15:04
     */
    public static function cancelOrderSubsequentHandle($order){

        if($order['coupon_id']){
            CouponList::where(['user_id'=>$order['user_id'],'order_id'=>$order['id']])
                ->update(['status'=>CouponListEnum::STATUS_NOT_USE,'order_id'=>'','use_time'=>'','update_time'=>time()]);
        }

        if ($order['use_integral']) {
            User::where('id',$order['user_id'])->inc('integral',$order['use_integral'])->update();
            $integral_log=array(
                'order_id'=>$order['id'],
                'user_id'=>$order['user_id'],
                'num'=>$order['use_integral'],
                'create_time'=>time(),
                'update_time'=>time(),
                'remark'=>'取消订单退回',
            );
            (new IntegralLogic())->addIntegralLog($integral_log);
        }

    }
}