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
use app\common\model\RechargeTemplate;
use app\common\server\ConfigServer;
use app\common\server\FileServer;
use app\common\server\storage\Driver as StorageDriver;
use app\common\server\UrlServer;
use app\common\server\WeChatServer;
use app\common\server\JsonServer;
use Endroid\QrCode\QrCode;
use think\facade\Cache;
use think\Exception;
use EasyWeChat\Factory;

use app\common\model\order\Order;
use app\common\model\user\User;
use app\common\model\IntegralLog;
use app\admin\logic\integral\RuleLogic;

class IntegralLogic
{
    
    //订单完成进行赠送积分操作
    public function dealOrderIntegralConfirm($order_id){
        $order=Order::find($order_id);
        #积分配置
        $config=RuleLogic::getConfig();
        #每消费一元赠送多少积分
        $money_to_num=isset($config['money_to_num'])?$config['money_to_num']:0;
        if($order && $order['order_amount']>0 && $money_to_num>0){

            

            //增加用户余额
            $user = User::find($order['user_id']);
            $user->integral = ['inc', round($order['order_amount']*$money_to_num,2)];
            $user->save();
            $log_info=array(
                'user_id'=>$order['user_id'],
                'order_id'=>$order['id'],
                'create_time'=>time(),
                'update_time'=>time(),
                'num'=>round($order['order_amount']*$money_to_num,2),
                'remark'=>'消费赠送积分'
            );
            $this->addIntegralLog($log_info);
        }
        
    }


    //积分日志
    public function addIntegralLog($info)
    {
        $log=new IntegralLog();
        $log->insert($info);

    }

    

    

}
