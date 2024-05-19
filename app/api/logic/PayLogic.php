<?php

namespace app\api\logic;

use app\common\{basics\Logic,
    enum\ClientEnum,
    enum\OrderEnum,
    enum\PayEnum,
    model\AccountLog,
    logic\AccountLogLogic,
    model\order\OrderGoods,
    model\order\Order,
    model\order\OrderTrade,
    model\Pay,
    model\RechargeOrder,
    model\shop\ShopGoods,
    model\user\User,
    server\ConfigServer,
    server\WeChatPayServer};
use think\{Exception, facade\Db, facade\Event};


/**
 * Class PayLogic
 * @package app\api\logic
 */
class PayLogic extends Logic
{
    /**
     * @notes
     * @param $post
     * @param $client_id
     * @return array|bool|\think\response\Json
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author cjhao
     * @date 2021/10/11 9:48
     */
    public static function unifiedPay($post,$client_id)
    {

        try {
            Db::startTrans();
            $data = [];
            if ($post['from'] == 'order') {
                // 更新支付方式
                $order = Order::findOrEmpty($post['order_id']);
                $order->pay_way = $post['pay_way'];
                $order->save();
                if(0 == $order->order_amount){
                    self::balancePay($order->id);
                    Db::commit();
                    return $data;
                }
            }
            if ($post['from'] == 'recharge') {
                $order = RechargeOrder::findOrEmpty($post['order_id']);
            }
            
            switch ($post['pay_way']) {
                case PayEnum::WECHAT_PAY://微信支付
                    $data = self::wechatPay($order->id, $post['from'],$client_id);
                    break;
                case PayEnum::BALANCE_PAY://余额支付
                    self::balancePay($order->id);
                    break;
            }

            Db::commit();
            return $data;

        } catch (Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }



    }

    /**
     * @notes 余额支付
     * @param $order_id
     * @param $post
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/18 16:01
     */
    public static function balancePay($order_id)
    {

        $order = Order::where([
            ['del', '=', 0],
            ['id', '=', $order_id]
        ])->find();

        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        if ( PayEnum::ISPAID == $order->pay_status) {
            throw new Exception('订单已支付');
        }
        $user_balance = User::where(['id' => $order->user_id])->value('user_money');
        if ($user_balance < $order->order_amount) {
            throw new Exception('余额不足');
        }
        //修改用户消费累计额度
        $user = User::find($order->user_id);
        $user->total_order_amount = ['inc', $order->order_amount];
        $user->user_money = ['dec', $order->order_amount];
        $user->save();
        //记录余额
        AccountLogLogic::AccountRecord($order->user_id, $order->order_amount, 2, AccountLog::balance_pay_order);
        //增加商品销量
        $order_goods_list = OrderGoods::where('order_id', $order->id)->select();
        foreach ($order_goods_list as $order_goods){
            ShopGoods::where(['goods_id' => $order_goods->goods_id,'shop_id'=>$order_goods->shop_id])
                ->inc('sales_sum',$order_goods->goods_num)
                ->update();
        }
        //更新订单状态
        Order::where(['id'=>$order_id])
            ->update([
                'pay_status'    => PayEnum::ISPAID,
                'order_status'  => OrderEnum::ORDER_STATUS_DELIVERY,
                'pay_way'       => OrderEnum::PAY_WAY_BALANCE,
                'pay_time'      => time()
            ]);
        //通知用户
//            event('Notice', [
//                'scene' => NoticeEnum::ORDER_PAY_NOTICE,
//                'mobile' => $user['mobile'],
//                'params' => ['order_id' => $order['id'], 'user_id' => $order['user_id']]
//            ]);

        //打印订单
        event('Printer', [
            'order_id' => $order['id'],
        ]);

        return true;
    }

    /**
     * @notes 微信支付
     * @param $order_id
     * @param $form
     * @param $client
     * @return \think\response\Json
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author suny
     * @date 2021/7/13 6:24 下午
     */
    public static function wechatPay($order_id, $from, $client)
    {
        switch ($from) {
            case "order":
                $order = Order::where([
                    ['del', '=', 0],
                    ['id', '=', $order_id]
                ])->find();
                break;
            case "recharge":
                $order = RechargeOrder::where([
                    ['id', '=', $order_id]
                ])->find();
                break;
        }
        if (empty($order)) {
            throw new Exception('订单不存在');
        }
        if (PayEnum::ISPAID == $order->pay_status) {
            throw new Exception('订单已支付');
        }
        // 这里进行微信支付
        $res = WeChatPayServer::unifiedOrder($from, $order, $client);

        return [
            'code' => 1,
            'msg' => '微信支付发起成功',
            'show' => 0,
            'data' => $res
        ];


    }


    /**
     * @notes 获取支付方式
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/10/11 9:49
     */
    public static function getPayWay($user_id, $client, $params)
    {
        $payModel = new Pay();
        $payway = $payModel->where(['status' => 1])->order('sort')->hidden(['config'])->select()->toArray();
        foreach ($payway as $k => &$item) {
            if ($item['code'] == 'wechat') {
                $item['extra'] = '微信快捷支付';
                $item['pay_way'] = PayEnum::WECHAT_PAY;
            }

            if ($item['code'] == 'balance') {
                $user_money = Db::name('user')->where(['id' => $user_id])->value('user_money');
                $item['extra'] = '可用余额:' . $user_money;
                $item['pay_way'] = PayEnum::BALANCE_PAY;
                if($params['from'] == 'recharge') {
                    unset($payway[$k]);
                }
            }

        }

        if($params['from'] == 'order') {
            $order = Order::findOrEmpty($params['order_id']);
        } else {
            $order = RechargeOrder::findOrEmpty($params['order_id']);
        }
        return [
            'pay_way' => $payway,
            'order_amount' => $order['order_amount'],
            'cancel_time' => $order['cancel_time'],
        ];
    }

}
