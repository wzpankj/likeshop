<?php
namespace  app\admin\controller\recharge_courtesy;

use app\common\basics\AdminBase;
use app\admin\logic\recharge_courtesy\RuleLogic;
use app\common\server\JsonServer;

/**
 * 充值规则
 * Class Rule
 * @package app\admin\controller
 */
class Rule extends AdminBase
{
    /**
     * 充值规则
     */
    public function config()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            RuleLogic::setConfig($post);
            return JsonServer::success('设置成功');
        }

        return view('config', [
            'config' => RuleLogic::getConfig(),
            'rule'   => RuleLogic::getRule()
        ]);
    }
}