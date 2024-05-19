<?php
namespace app\api\controller;

use app\common\basics\Api;
use app\common\server\JsonServer;
use app\api\logic\RechargeLogic;
use app\api\validate\RechargeValidate;
use think\exception\ValidateException;

/**
 * 充值接口
 * Class Recharge
 * @package app\api\controller
 */
class Recharge extends Api
{
    public $like_not_need_login = ['rechargerule'];

    /**
     * note 充值规则
     */
    public function rechargeRule(){
        $list = RechargeLogic::rechargeRule();
        return JsonServer::success('', $list);
    }

    /**
     * 充值
     */
    public function recharge(){
        try{
            $post = $this->request->post();
            validate(RechargeValidate::class)->check($post);
        }catch(ValidateException $e) {
            return JsonServer::error($e->getError());
        }
        $result = RechargeLogic::recharge($this->user_id,$this->client,$post);
        if($result === false) {
            return JsonServer::error(RechargeLogic::getError());
        }
        return JsonServer::success('', $result);
    }

    /**
     * 充值记录
     */
    public function rechargeRecord()
    {
        $get = $this->request->get();
        $get['page_no'] = $get['page_no'] ?? $this->page_no;
        $get['page_size'] = $get['page_size'] ?? $this->page_size;
        $get['user_id'] = $this->user_id;
        $result =  RechargeLogic::rechargeRecord($get);
        return JsonServer::success('', $result);
    }
}
