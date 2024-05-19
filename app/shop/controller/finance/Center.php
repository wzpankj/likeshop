<?php


namespace app\shop\controller\finance;


use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\finance\CenterLogic;

/**
 * 门店财务中心控制器
 * Class Center
 * @package app\shop\controller\finance
 */
class Center extends ShopBase
{
    /**
     * @notes 财务中心
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/28 10:37 上午
     */
    public function center()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $lists = CenterLogic::center($get, $this->shop_id);
            return JsonServer::success('获取成功', $lists);
        }

        $week = [
            date('Y-m-d H:i:s', strtotime(date("Y-m-d", strtotime("-7 day")))),
            date('Y-m-d 23:59:59', time())
        ];
        return view('', [
            'statistics' => CenterLogic::statistics($this->shop_id),
            'week' => $week,
        ]);
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/28 10:41 上午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',CenterLogic::exportFile($get,$this->shop_id));
    }
}