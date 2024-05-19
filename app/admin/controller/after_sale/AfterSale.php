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
// | Author: LikeShopTeam
// +----------------------------------------------------------------------


namespace app\admin\controller\after_sale;

use app\common\basics\AdminBase;
use app\common\server\JsonServer;
use app\common\model\Freight;
use app\common\model\after_sale\AfterSale as AfterSaleModel;
use app\admin\logic\after_sale\AfterSaleLogic;
use think\exception\ValidateException;

/**
 * Class AfterSale
 * @package app\admin\controller\after_sale
 */
class AfterSale extends AdminBase
{
    /**
     * @notes 售后列表
     * @return \think\response\Json|\think\response\View
     * @author suny
     * @date 2021/7/13 6:59 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', AfterSaleLogic::list($get));
        }
        $data = AfterSaleLogic::list();
        // 售后状态
        $status = AfterSaleModel::getStatusDesc(true);
        $status = AfterSaleLogic::getStatus($status);
        $all = AfterSaleLogic::getAll();
        return view('', [
            'data' => $data,
            'all' => $all,
            'status' => $status
        ]);
    }

    /**
     * @notes 售后详情
     * @return \think\response\View
     * @author suny
     * @date 2021/7/13 6:59 下午
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $detail = AfterSaleLogic::getDetail($id);
        return view('', [
            'detail' => $detail
        ]);
    }
}