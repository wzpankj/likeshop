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

namespace app\admin\logic\recharge_courtesy;

use app\common\basics\Logic;
use app\common\model\RechargeRule;
use app\common\server\ArrayServer;
use app\common\server\ConfigServer;

/**
 * 平台充值规则-逻辑层
 * Class RuleLogic
 * @package app\admin\logic\recharge_courtesy
 */
class RuleLogic extends Logic
{
    public static function getConfig()
    {
        $config = [
            'app_status'            =>  ConfigServer::get('recharge', 'app_status', 0),
            'min_recharge_amount'   =>  ConfigServer::get('recharge', 'min_recharge_amount', 0),
        ];
        return $config;
    }

    public static function getRule()
    {
        return RechargeRule::select()->toArray();
    }

    public static function setConfig($config_list)
    {
        $now_time = time();

        foreach ($config_list as $config_name => $config_value){
            if(!is_array($config_value)){
                if ($config_name == 'min_recharge_amount'){
                    if(!$config_value){
                        $config_value = 0.00;
                    }else{
                        $config_value = sprintf("%.2f",substr(sprintf("%.3f", $config_value), 0, -1));
                    }
                }

                $config_value2[] = $config_value;
                ConfigServer::set('recharge',$config_name,$config_value);
            }else{
                // 拼接添加时间
                if($config_name == 'id'){
                    for ($i = 0;$i<count($config_value);$i++){
                        $config_list['create_time'][$i] = $now_time;
                    }
                }
            }
        }
        
        $spec_lists = ArrayServer::form_to_linear($config_list);
        $recharge_rule = new RechargeRule;
        $recharge_rule::where('id','>',0)->delete();
        $recharge_rule->saveAll($spec_lists);
        return true;
    }
}