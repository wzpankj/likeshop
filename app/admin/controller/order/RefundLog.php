<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\admin\controller\order;


use app\admin\logic\order\RefundLogLogic;
use app\common\basics\AdminBase;
use app\common\enum\OrderRefundEnum;
use app\common\server\JsonServer;

/**
 * 平台退款记录控制器
 * Class RefundLog
 * @package app\shop\controller\order
 */
class RefundLog extends AdminBase
{
    /**
     * @notes 退款记录列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/22 4:32 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', RefundLogLogic::lists($get));
        }

        $get = $this->request->get();
        return view('', [
            'statistics' => RefundLogLogic::statistics($get),
            'refund_type' => OrderRefundEnum::getRefundType(true),
            'refund_way' => OrderRefundEnum::getRefundWay(true),
        ]);
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/22 4:40 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',RefundLogLogic::exportFile($get));
    }

    /**
     * @notes 退款订单数据统计
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/20 2:06 下午
     */
    public function totalCount()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('获取成功', RefundLogLogic::statistics($get));
        }
    }
}