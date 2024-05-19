<?php

namespace app\admin\logic\finance;

use app\common\basics\Logic;
use app\common\model\user\User;
use app\common\server\UrlServer;
use app\common\model\AccountLog;

/**
 * 财务-用户相关
 * Class WithdrawLogic
 * @package app\admin\logic\finance
 */
class UserLogic extends Logic
{
    /**
     * @notes 余额明细列表
     * @param $get
     * @return array
     * @author heshihu
     * @date 2021/9/14 10:39
     */
    public static function account($get)
    {
        $where = [];

        // 明细类型
        $source_type = [AccountLog::recharge_money, AccountLog::balance_pay_order];
        if (isset($get['type']) && !empty($get['type'])) {
            switch ($get['type']) {
                case 'recharge_money' :
                    $type = AccountLog::recharge_money;
                    break;
                case 'balance_pay_order' :
                    $type = AccountLog::balance_pay_order;
                    break;
            }
            $where[] = ['a.source_type', '=', $type];
        } else {
            $where[] = ['a.source_type', 'in', $source_type];
        }

        //明细搜索
        if (isset($get['keyword']) && !empty($get['keyword'])) {
            $where[] = ['u.sn|u.nickname', 'like', '%' . $get['keyword'] . '%'];
        }

        //明细时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['a.create_time', '>=', strtotime($get['start_time'])];
        }

        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['a.create_time', '<=', strtotime($get['end_time'])];
        }

        $count = AccountLog::alias('a')
            ->join('user u', 'u.id = a.user_id')
            ->where($where)
            ->count();

        $lists = AccountLog::alias('a')
            ->join('user u', 'u.id = a.user_id')
            ->where($where)
            ->field('a.*,u.nickname,u.sn as user_sn,u.avatar')
            ->withAttr('avatar',function ($value,$data){
                return UrlServer::getFileUrl($value);
            })
            ->withAttr('change_amount',function ($value,$data){
                $tip  = '￥';
                if($data['change_type'] == 2){
                    $tip = '-￥';
                }
                return $tip.$value;
            })
            ->withAttr('left_amount',function ($value,$data){
                $tip  = '￥';
                return $tip.$value;
            })
            ->page($get['page'], $get['limit'])
            ->order('a.id desc')
            ->select();


        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 导出列表
     * @param $get
     * @return array
     * @author ljj
     * @date 2021/10/12 3:02 下午
     */
    public static function exportFile($get)
    {
        $where = [];

        // 明细类型
        $source_type = [AccountLog::recharge_money, AccountLog::balance_pay_order];
        if (isset($get['type']) && !empty($get['type'])) {
            switch ($get['type']) {
                case 'recharge_money' :
                    $type = AccountLog::recharge_money;
                    break;
                case 'balance_pay_order' :
                    $type = AccountLog::balance_pay_order;
                    break;
            }
            $where[] = ['a.source_type', '=', $type];
        } else {
            $where[] = ['a.source_type', 'in', $source_type];
        }

        //明细搜索
        if (isset($get['keyword']) && !empty($get['keyword'])) {
            $where[] = ['u.sn|u.nickname', 'like', '%' . $get['keyword'] . '%'];
        }

        //明细时间
        if (isset($get['start_time']) && $get['start_time'] != '') {
            $where[] = ['a.create_time', '>=', strtotime($get['start_time'])];
        }

        if (isset($get['end_time']) && $get['end_time'] != '') {
            $where[] = ['a.create_time', '<=', strtotime($get['end_time'])];
        }

        $lists = AccountLog::alias('a')
            ->join('user u', 'u.id = a.user_id')
            ->where($where)
            ->field('a.*,u.nickname,u.sn as user_sn,u.avatar')
            ->withAttr('avatar',function ($value,$data){
                return UrlServer::getFileUrl($value);
            })
            ->withAttr('change_amount',function ($value,$data){
                $tip  = '￥';
                if($data['change_type'] == 2){
                    $tip = '-￥';
                }
                return $tip.$value;
            })
            ->withAttr('left_amount',function ($value,$data){
                $tip  = '￥';
                return $tip.$value;
            })
            ->order('a.id desc')
            ->select();

        $exportTitle = ['用户编号','用户昵称','变动金额','剩余金额','明细类型','来源单号','记录时间'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            $exportData[] = [$item['user_sn'], $item['nickname'], $item['change_amount'], $item['left_amount'], $item['source_type'], $item['source_sn'], $item['create_time']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'余额明细列表'.date('Y-m-d H:i:s',time())];
    }

    /**
     * @notes 资产概况
     * @return array
     * @author ljj
     * @date 2021/10/12 3:17 下午
     */
    public static function overview()
    {
        return [
            'total_recharge_amount' => User::where(['del'=>0])->sum('total_recharge_amount'),
        ];
    }
}