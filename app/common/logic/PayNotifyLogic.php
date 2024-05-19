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

namespace app\common\logic;

use app\common\{enum\NoticeEnum,
    enum\PayEnum,
    enum\OrderEnum,
    model\shop\Shop,
    model\user\User,
    model\AccountLog,
    model\order\Order,
    enum\OrderLogEnum,
    model\RechargeOrder,
    model\order\OrderLog,
    server\ConfigServer};
use think\{
    Exception,
    facade\Db,
    facade\Log
};

/**
 * 支付成功后处理订单状态
 * Class PayNotifyLogic
 * @package app\api\logic
 */
class PayNotifyLogic
{
    /**
     * @notes 回调处理
     * @param $action
     * @param $order_sn
     * @param array $extra
     * @return bool|string
     * @throws \think\exception\PDOException
     * @author suny
     * @date 2021/7/13 6:32 下午
     */
    public static function handle($action, $order_sn, $extra = [])
    {

        Db::startTrans();
        try {
            self::$action($order_sn, $extra);
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $record = [
                __CLASS__, __FUNCTION__, $e->getFile(), $e->getLine(), $e->getMessage()
            ];
            Log::record(implode('-', $record));
            return $e->getMessage();
        }
    }

    /**
     * @notes 订单回调
     * @param $order_sn
     * @param array $extra
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/17 16:17
     */
    private static function order($order_sn, $extra = [])
    {
        $now = time();
        $order = Order::where('order_sn', $order_sn)
            ->find()->toArray();

        //更新订单状态
        $data = [
            'pay_status'    => PayEnum::ISPAID,
            'pay_time'      => $now,
            'order_status'  => OrderEnum::ORDER_STATUS_DELIVERY,//待取餐
            'cancel_time'   => '',
            'update_time'   => $now
        ];
        //如果返回了第三方流水号
        if (isset($extra['transaction_id'])) {
            $data['transaction_id'] = $extra['transaction_id'];
        }
        Order::update($data, ['id' => $order['id']]);
        //订单的后续操作
        self::orderSubsequentHandle($order);

    }

    /**
     * @notes 订单支付后续操作
     * @param $order
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/17 18:04
     */
    public static function orderSubsequentHandle(array $order)
    {

        // 增加一条订单日志
        $order_log = new OrderLog();
        $order_log->type        = OrderLogEnum::TYPE_USER;
        $order_log->channel     = OrderLogEnum::USER_PAID_ORDER;
        $order_log->order_id    = $order['id'];
        $order_log->handle_id   = $order['user_id'];
        $order_log->shop_id     = $order['shop_id'];
        $order_log->content     = OrderLogEnum::getLogDesc(OrderLogEnum::USER_PAID_ORDER);
        $order_log->create_time = time();
        $order_log->save();

        //修改用户消费累计额度
        $user = User::find($order['user_id']);
        $user->total_order_amount = ['inc', $order['order_amount']];
        $user->save();

        $shop_take_out_notice = ConfigServer::get('shop_notice','take_out_notice',0,$order['shop_id']);

        if(1 == $shop_take_out_notice){
            $shop = Shop::where(['id'=>$order['shop_id']])->field('phone,name')->find();
            //通知商家
            event('Notice', [
                'scene'     => NoticeEnum::TAKEOUT_NOTICE,
                'mobile'    => $shop['phone'],
                'params'    => ['shop_name'=>$shop['name']]
            ]);
        }
        //打印订单
        event('Printer', [
            'order_id' => $order['id'],
        ]);
    }

    /**
     * @notes 充值回调
     * @param $order_sn
     * @param array $extra
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author heshihu
     * @date 2021/9/9 14:19
     */
    private static function recharge($order_sn, $extra = [])
    {

        $new = time();
        $recharge_order = new RechargeOrder();
        $order = $recharge_order->where(['order_sn' => $order_sn])->find();
        $update_data['pay_time'] = $new;
        $update_data['pay_status'] = PayEnum::ISPAID;
        if (isset($extra['transaction_id'])) {
            $update_data['transaction_id'] = $extra['transaction_id'];
        }
        $recharge_order->where(['id' => $order['id']])->update($update_data);
        $user = User::find($order['user_id']);
        $total_money = $order['order_amount'] + $order['give_money'];
//        $total_integral = $order['give_integral'];
        $user->user_money = ['inc', $total_money];
//        $user->user_integral = ['inc', $total_integral];
//        $user->user_growth = ['inc', $order['give_growth']];
        $user->total_recharge_amount = ['inc', $total_money];
        $user->save();
        //记录余额充值
        $order['order_amount'] > 0 && AccountLogLogic::AccountRecord($user->id, $order['order_amount'], 1, AccountLog::recharge_money,'',$order['id'],$order_sn);
        //记录充值赠送
        $order['give_money'] > 0 && AccountLogLogic::AccountRecord($user->id, $order['give_money'], 1, AccountLog::recharge_give_money,'',$order['id'],$order_sn);
        //记录积分
//        $total_integral > 0 && AccountLogLogic::AccountRecord($user->id, $total_integral, 1, AccountLog::recharge_give_integral);
        //记录成长值
//        $order['give_growth'] > 0 && AccountLogLogic::AccountRecord($user->id, $order['give_growth'], 1, AccountLog::recharge_give_growth);
    }

}