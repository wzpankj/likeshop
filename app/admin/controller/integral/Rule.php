<?php
namespace  app\admin\controller\integral;

use app\common\basics\AdminBase;
use app\admin\logic\integral\RuleLogic;
use app\common\server\JsonServer;

/**
 * 积分规则
 * Class Rule
 * @package app\admin\controller
 */
class Rule extends AdminBase
{
    /**
     * 积分规则
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