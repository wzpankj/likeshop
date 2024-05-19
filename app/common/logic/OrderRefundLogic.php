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
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\common\logic;


use app\common\enum\OrderEnum;
use app\common\enum\OrderGoodsEnum;
use app\common\enum\OrderLogEnum;
use app\common\enum\OrderRefundEnum;
use app\common\enum\PayEnum;
use app\common\enum\RefundEnum;
use app\common\model\order\Order;
use app\common\model\AccountLog;
use app\common\model\order\Order as CommonOrder;
use app\common\model\order\OrderGoods;
use app\common\model\order\OrderLog;
use app\common\model\order\OrderRefund;
use app\common\model\Pay;
use app\common\model\user\User;
use app\common\server\AliPayServer;
use app\common\server\WeChatPayServer;
use app\common\server\WeChatServer;
use think\Exception;
use think\facade\Db;
use think\facade\Event;

/**
 * 订单退款逻辑
 * Class OrderRefundLogic
 * @package app\common\logic
 */
class OrderRefundLogic
{

    /**
     * Notes:  取消订单
     * @param $order_id
     * @param int $handle_type
     * @param int $handle_id
     * @author 段誉(2021/1/28 15:23)
     * @return Order
     */
    public static function cancelOrder($order_id, $handle_type = OrderLogEnum::TYPE_SYSTEM, $handle_id = 0)
    {
        //更新订单状态
        $order = Order::where('id',$order_id)->find();
        $order->order_status = OrderEnum::ORDER_STATUS_DOWN;
        $order->cancel_time = time();
        $order->update_time = time();
        $order->save();

        //取消订单后操作
        Event::listen('cancel_order', ['order_id'  => $order_id, 'handle_id' => $handle_id, 'handle_type' => $handle_type]);
        return $order;
    }


    /**
     * Notes: 处理订单退款(事务在取消订单逻辑处)
     * @param $order
     * @param $order_amount
     * @param $refund_amount
     * @author 段誉(2021/1/28 15:23)
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function refund($order, $order_amount, $refund_amount, $extend = [])
    {
        //退款记录
        $refund_id = self::addRefundLog($order, $order_amount, $refund_amount, $extend);

        $refund_way = $order['pay_way'];
        //退款到余额
        if (isset($extend['refund_way']) && $extend['refund_way'] == 1) {
            $refund_way = PayEnum::BALANCE_PAY;
        }
        //现金退款
        if (isset($extend['refund_way']) && $extend['refund_way'] == 2) {
            return true;
        }
        switch ($refund_way) {
            //余额退款
            case PayEnum::BALANCE_PAY:
                self::balancePayRefund($order, $refund_amount, $refund_id);
                break;
            //微信退款
            case PayEnum::WECHAT_PAY:
                self::wechatPayRefund($order, $refund_id);
                break;
        }
    }


    /**
     * Notes: 增加退款记录
     * @param $order
     * @param $order_amount
     * @param $refund_amount
     * @param string $result_msg
     * @author 段誉(2021/1/28 15:23)
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addRefundLog($order, $order_amount, $refund_amount, $extend = [], $result_msg = '退款中')
    {
        $data = [
            'shop_id' => $order['shop_id'],
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'refund_sn' => createSn('order_refund', 'refund_sn'),
            'order_amount' => $order['order_amount'],
            'refund_amount' => $refund_amount,
            'transaction_id' => $order['transaction_id'],
            'refund_status' => OrderRefundEnum::REFUND_STATUS_ING,
            'refund_way' => $extend['refund_way'] ?? OrderRefundEnum::REFUND_WAY_ORIGINAL,
            'refund_remark' => $extend['refund_remark'] ?? '',
            'create_time' => time(),
            'refund_msg' => json_encode($result_msg, JSON_UNESCAPED_UNICODE),
        ];
        if (isset($extend['refund_type']) && !empty($extend['refund_type'])) {
            $data['refund_type'] = $extend['refund_type'];
        }
        return Db::name('order_refund')->insertGetId($data);
    }


    /**
     * Notes: 余额退款
     * @param $order
     * @param $refund_amount
     * @author 段誉(2021/1/28 15:24)
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function balancePayRefund($order, $refund_amount, $refund_id)
    {
        //增加用户余额
        $user = User::find($order['user_id']);
        $user->user_money = ['inc', $refund_amount];
        $user->save();

        //退款成功，更新订单退款信息
        self::editOrderRefundInfo($order, $refund_amount,OrderEnum::REFUND);

        //更新退款表状态
        $order_refund = OrderRefund::find($refund_id);
        $order_refund->refund_status = OrderRefundEnum::REFUND_STATUS_COMPLETE;
        $order_refund->arrival_time = time();
        $order_refund->save();

        AccountLogLogic::AccountRecord(
            $order['user_id'],
            $refund_amount,
            1,
            AccountLog::cancel_order_refund,
            '',
            $order['id'],
            $order['order_sn']
        );
        return true;
    }


    /**
     * Notes: 微信支付退款
     * @param $order (订单信息)
     * @param $refund_id (退款记录id)　
     * @author 段誉(2021/1/27 16:04)
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function wechatPayRefund($order, $refund_id)
    {
        $config = WeChatServer::getPayConfigBySource($order['order_source'])['config'];

        if (empty($config)) {
            throw new Exception('请联系管理员设置微信相关配置!');
        }

        if (!isset($config['cert_path']) || !isset($config['key_path'])) {
            throw new Exception('请联系管理员设置微信证书!');
        }
        if (!file_exists($config['cert_path']) || !file_exists($config['key_path'])) {
            throw new Exception('微信证书不存在,请联系管理员!');
        }

        $refund_log = Db::name('order_refund')->where(['id' => $refund_id])->find();

        $data = [
            'transaction_id' => $order['transaction_id'],
            'refund_sn' => $refund_log['refund_sn'],
            'total_fee' => $refund_log['order_amount'] * 100,//订单金额,单位为分
            'refund_fee' => intval($refund_log['refund_amount'] * 100),//退款金额
        ];
        $result = WeChatPayServer::refund($config, $data);
        if (isset($result['return_code']) && $result['return_code'] == 'FAIL') {
            throw new Exception($result['return_msg']);
        }

        if (isset($result['err_code_des'])) {
            throw new Exception($result['err_code_des']);
        }

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $update_data = [
                'wechat_refund_id' => $result['refund_id'] ?? 0,
                'refund_msg' => json_encode($result, JSON_UNESCAPED_UNICODE),
                'refund_status' => OrderRefundEnum::REFUND_STATUS_COMPLETE,
                'arrival_time' => time(),
            ];
            //更新退款日志记录
            Db::name('order_refund')->where(['id' => $refund_id])->update($update_data);

            //退款成功，更新订单退款信息
            self::editOrderRefundInfo($order, $refund_log['refund_amount'],OrderEnum::REFUND);

        } else {
            $update_data = [
                'refund_msg' => json_encode($result, JSON_UNESCAPED_UNICODE),
                'refund_status' => OrderRefundEnum::REFUND_STATUS_FAIL,
            ];
            //更新退款日志记录
            Db::name('order_refund')->where(['id' => $refund_id])->update($update_data);

            //退款失败，更新订单退款信息
            self::editOrderRefundInfo($order,0,OrderEnum::REFUND_FAIL);

            throw new Exception('微信支付退款失败');
        }
    }


    /**
     * Notes: 取消订单退款失败增加错误记录
     * @param $order
     * @param $err_msg
     * @author 段誉(2021/1/28 15:24)
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addErrorRefund($order, $err_msg)
    {
        //订单退款状态更新为退款失败
        self::editOrderRefundInfo($order,0,OrderEnum::REFUND_FAIL);

        //订单退款表增加订单失败记录
        $refund_data = [
            'shop_id' => $order['shop_id'],
            'order_id' => $order['id'],
            'user_id' => $order['user_id'],
            'refund_sn' => createSn('order_refund', 'refund_sn'),
            'order_amount' => $order['order_amount'],//订单应付金额
            'refund_amount' => $order['order_amount'],//订单退款金额
            'transaction_id' => $order['transaction_id'],
            'refund_status' => OrderRefundEnum::REFUND_STATUS_FAIL,
            'refund_way' => OrderRefundEnum::REFUND_WAY_ORIGINAL,
            'refund_type' => OrderRefundEnum::REFUND_TYPE_TOTAL,
            'create_time' => time(),
            'refund_msg' => json_encode($err_msg, JSON_UNESCAPED_UNICODE),
        ];
        return Db::name('order_refund')->insertGetId($refund_data);
    }

    /**
     * @notes 更改订单表退款信息
     * @param $order
     * @param $refund_amount
     * @param $refund_status
     * @author ljj
     * @date 2021/9/22 6:27 下午
     */
    public static function editOrderRefundInfo($order,$refund_amount,$refund_status)
    {
        Order::where(['id' => $order['id']])->update([
            'refund_amount' => $order['refund_amount'] + $refund_amount,
            'surplus_refund_amount' => (($order['surplus_refund_amount'] == null) ? $order['order_amount'] : $order['surplus_refund_amount']) - $refund_amount,
            'refund_status' => $refund_status,
            'refund_time' => time(),
        ]);
    }

}