<?php
namespace app\common\logic;

use app\common\basics\Logic;
use app\common\model\user\User;
use app\common\model\AccountLog;
use app\admin\logic\user\LevelLogic;

class AccountLogLogic extends Logic
{
    /**
     * Notes:记录会员账户流水，如果变动类型是成长值，且是增加的，则调用更新会员等级方法。该方法应在添加用户账户后调用
     * @param int $user_id  用户id
     * @param float $amount 变动数量
     * @param int $change_type  变动类型：1-增加；2-减少
     * @param int $source_type 来源类型
     * @param string $remark 说明
     * @param string $source_id 来源id
     * @param string $source_sn 来源单号
     * @param string $extra 额外字段说明
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function AccountRecord($user_id,$amount,$change_type,$source_type,$remark ='',$source_id ='',$source_sn='',$extra=''){
        $user = User::findOrEmpty($user_id);
        if($user->isEmpty()){
            return false;
        }
        $type = AccountLog::getChangeType($source_type);
        $left_amount = 0;
        switch ($type){
            case 'money':
                $left_amount = $user->user_money;
                break;
            case 'integral':
                $left_amount = $user->user_integral;
                break;
            case 'growth':
                $left_amount = $user->user_growth;
                break;
            case 'earnings':
                $left_amount = $user->earnings;
        }
        $account_log = new AccountLog();
        $account_log->log_sn = createSn('account_log','log_sn','',4);
        $account_log->user_id = $user_id;
        $account_log->source_type = $source_type;
        $account_log->source_id = $source_id;
        $account_log->source_sn = $source_sn;
        $account_log->change_amount = $amount;
        $account_log->left_amount = $left_amount;
        $account_log->remark = AccountLog::getRemarkDesc($source_type,$source_sn,$remark);
        $account_log->extra = $extra;
        $account_log->change_type = $change_type;
        $account_log->create_time = time();
        $account_log->save();

        //更新会员等级
        if($type === 'growth' && $change_type == 1){
            LevelLogic::updateUserLevel([$user]);
        }
        return true;
    }
}