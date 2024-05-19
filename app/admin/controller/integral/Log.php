<?php
namespace  app\admin\controller\recharge_courtesy;

use app\admin\logic\goods\GoodsLogic;
use app\common\basics\AdminBase;
use app\admin\logic\recharge_courtesy\LogLogic;
use app\common\model\RechargeOrder;
use app\common\server\JsonServer;

/**
 * 充值记录
 * Class Log
 * @package app\admin\controller
 */
class Log extends AdminBase
{
    /**
     * 列表
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', LogLogic::lists($get));
        }

        // 订单状态
        $pay_status = RechargeOrder::getPayStatus(true);
        return view('', ['pay_status' => $pay_status]);
    }

}