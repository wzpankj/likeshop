<?php
namespace app\api\logic;

use app\common\basics\Logic;
use app\common\model\RechargeRule;
use app\common\model\RechargeOrder;
use app\common\server\ConfigServer;
use app\common\enum\PayEnum;
use think\facade\Db;
use app\common\logic\AccountLogLogic;
use app\common\model\AccountLog;

/**
 * 充值逻辑层
 * Class RechargeLogic
 * @package app\api\logic
 */
class RechargeLogic extends Logic
{
    public static function rechargeRule(){
        $list = RechargeRule::where([['id','>',0]])
            ->select()
            ->toArray();

        foreach ($list as &$item){
            $item['tips'] = '';
            if($item['give_balance'] > 0){
                $item['tips'] = '送'.intval($item['give_balance']).'元';
            }
        }
        return $list;
    }

    public static function recharge($user_id,$client,$post)
    {
        try{


            if(isset($post['id'])){
                //根据充值规则充值
                $rule = RechargeRule::where(['id'=>$post['id']])->findOrEmpty();
                if($rule->isEmpty()) {
                    throw new \think\Exception('充值规则不存在');
                }
                $money = $rule['recharge_amount'];
                $give_money = $rule['give_balance'];

            }else{
                //自定义充值金额
                $rule = RechargeRule::where(['recharge_amount'=>$post['money']])->findOrEmpty();
                $money = $post['money'];
                $give_money = 0;
                if(!$rule->isEmpty()){
                    $money = $rule['recharge_amount'];
                    $give_money = $rule['give_balance'];
                }
            }

            $add_order = [
                'user_id'       => $user_id,
                'order_sn'      => createSn('recharge_order','order_sn'),
                'order_amount'  => $money,
                'order_source'  => $client,
                'pay_status'    => PayEnum::UNPAID,    //待支付状态；
                'pay_way'       => $post['pay_way'] ?? 1,
                'rule_id'       => $rule['id'] ?? 0,
                'give_money'    => $give_money,
                'create_time'   => time(),
            ];

            $id = Db::name('recharge_order')->insertGetId($add_order);
            if($id){
                return Db::name('recharge_order')->where(['id'=>$id])->find();
            }
            return [];
        }catch(\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    public static function rechargeRecord($get)
    {
        $list = RechargeOrder::field('order_sn, order_amount, give_money, create_time')
            ->where([
                'user_id' => $get['user_id'],
                'pay_status' => PayEnum::ISPAID, // 已支付的
            ])
            ->order('create_time', 'desc')
            ->page($get['page_no'], $get['page_size'])
            ->select()
            ->toArray();
        $count = RechargeOrder::where([
                'user_id' => $get['user_id'],
                'pay_status' => PayEnum::ISPAID
            ])
            ->count();

        foreach($list as &$item) {
            if($item['give_money'] > 0) {
                $item['desc'] = '充值'. clearZero($item['order_amount']) . '赠送' . clearZero($item['give_money']);
            }else{
                $item['desc'] = '充值'. clearZero($item['order_amount']);
            }
            $item['total'] = $item['order_amount'] + $item['give_money']; // 充值金额 + 赠送金额
        }

        $result = [
            'count' => $count,
            'lists' => $list,
            'more' =>  is_more($count, $get['page_no'], $get['page_size']),
            'page_no' =>  $get['page_no'],
            'page_size' =>  $get['page_size']
        ];

        return $result;
    }
}
