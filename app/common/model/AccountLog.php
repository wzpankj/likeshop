<?php
namespace app\common\model;
use app\common\basics\Models;

class AccountLog extends Models
{
    /*******************************
     ** 余额变动：100~199
     ** 积分变动：200~299
     ** 成长值变动：300~399
     ** 佣金变动: 400~499
     *******************************/
    const admin_add_money           = 100;
    const admin_reduce_money        = 101;
    const recharge_money            = 102;
    const balance_pay_order         = 103;
    const cancel_order_refund       = 104;
    const after_sale_refund         = 105;
    const withdraw_to_balance       = 106;
    const user_transfer_inc_balance = 107;
    const user_transfer_dec_balance = 108;
    const recharge_give_money       = 109;


    const money_change = [      //余额变动类型
        self::admin_add_money,self::admin_reduce_money,self::recharge_money,self::balance_pay_order,self::cancel_order_refund,self::after_sale_refund
        , self::withdraw_to_balance,self::user_transfer_inc_balance, self::user_transfer_dec_balance, self::recharge_give_money
    ];



    public static function getAcccountDesc($from = true){
        $desc = [
            self::admin_add_money               => '系统增加余额',
            self::admin_reduce_money            => '系统扣减余额',
            self::recharge_money                => '余额充值',
            self::balance_pay_order             => '余额支付',
            self::cancel_order_refund           => '取消订单退回余额',
            self::after_sale_refund             => '售后退回余额',
            self::user_transfer_inc_balance     => '会员转账(收入方)',
            self::user_transfer_dec_balance     => '会员转账(支出方)',
            self::recharge_give_money           => '充值赠送'
        ];
        if($from === true){
            return $desc;
        }
        return $desc[$from] ?? '';
    }
    //返回变动类型
    public static function getChangeType($source_type){
        $type = '';
        if(in_array($source_type,self::money_change)){
            $type = 'money';
        }
        return $type;
    }

    public static function getRemarkDesc($from,$source_sn,$remark =''){
        return $remark;
    }


    public static function getChangeAmountFormatAttr($value,$data){
        $amount = $value;
        if(!in_array($data['source_type'],self::money_change) && !in_array($data['source_type'],self::earnings_change)){
            $amount = intval($value);
        }
        if($data['change_type'] == 1){
            return '+'.$amount;
        }
        return '-'.$amount;
    }


    public static function getSourceTypeAttr($value,$data){
        return self::getAcccountDesc($value);

    }

    public function getCreateTimeFormatAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }

}