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

namespace app\shop\controller\order;


use app\common\basics\ShopBase;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\server\JsonServer;
use app\shop\logic\order\ShopOrderLogic;
use app\shop\validate\order\ShopOrderValidate;

/**
 * 到店订单控制器
 * Class ShopOrder
 * @package app\shop\controller\order
 */
class ShopOrder extends ShopBase
{
    /**
     * @notes 订单列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 3:23 下午
     */
    public function lists()
    {

        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', ShopOrderLogic::lists($get, $this->shop_id));
        }

        $get = $this->request->get();
        return view('', [
            'statistics' => ShopOrderLogic::statistics($get,$this->shop_id),
            'dining_type' => OrderEnum::getDiningTypeDesc(true),
            'pay_way' => PayEnum::getPayWay(true),
        ]);
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/16 2:35 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',ShopOrderLogic::exportFile($get,$this->shop_id));
    }

    /**
     * @notes 取消订单
     * @return \think\response\Json|void
     * @author ljj
     * @date 2021/9/16 3:23 下午
     */
    public function cancel()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            //验证
            (new ShopOrderValidate())->goCheck('Cencel',$post);

            $result = ShopOrderLogic::cancel($post, $this->shop_id);
            if (true !== $result) {
                return JsonServer::error($result);
            }
            return JsonServer::success('取消成功');
        }
    }

    /**
     * @notes 到店订单数据统计
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 6:30 下午
     */
    public function totalCount()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('获取成功', ShopOrderLogic::statistics($get,$this->shop_id));
        }
    }

    /**
     * @notes 确认取餐
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 6:35 下午
     */
    public function confirm()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post('');
            (new ShopOrderValidate())->goCheck('confirm',$post);
            ShopOrderLogic::confirm($post, $this->shop_id);
            return JsonServer::success('确认成功');
        }
    }

    /**
     * @notes 通知取餐
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/16 7:04 下午
     */
    public function notice()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            (new ShopOrderValidate())->goCheck('notice',$post);
            ShopOrderLogic::notice($post, $this->shop_id);
            return JsonServer::success('通知成功');
        }
    }

    /**
     * @notes 商家备注
     * @return \think\response\Json|void
     * @author ljj
     * @date 2021/9/17 10:13 上午
     */
    public function remarks()
    {
        // 获取的
        if ($this->request->isAjax() && $this->request->isGet()) {
            $get = $this->request->get();
            $detail = ShopOrderLogic::remarks($get, 'get',$this->shop_id);
            return JsonServer::success('获取成功', [$detail]);
        }
        // 提交的
        if ($this->request->isAjax() && $this->request->isPost()) {
            $post = $this->request->post();
            (new ShopOrderValidate())->goCheck('remarks',$post);
            ShopOrderLogic::remarks($post, 'post',$this->shop_id);
            return JsonServer::success('备注成功');
        }
    }

    /**
     * @notes 小票打印
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/17 10:31 上午
     */
    public function print()
    {
        $post = $this->request->post();
        $post['shop_id'] = $this->shop_id;
        (new ShopOrderValidate())->goCheck('print',$post);
        $result = ShopOrderLogic::print($post);
        if (true !== $result) {
            return JsonServer::error($result);
        }
        return JsonServer::success('打印机成功，如未出小票，请检查打印机是否在线');
    }

    /**
     * @notes 订单详情
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/17 10:32 上午
     */
    public function detail()
    {
        $id = $this->request->get('id');
        return view('', [
            'detail' => ShopOrderLogic::getDetail($id)
        ]);
    }

    /**
     * @notes 订单退款
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/18 3:30 下午
     */
    public function refund()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            (new ShopOrderValidate())->goCheck('refund',$post);

            $result = ShopOrderLogic::refund($post,$this->shop_id);
            if (true !== $result) {
                return JsonServer::error($result);
            }
            return JsonServer::success('发起退款成功');
        }

        $id = $this->request->get('id');
        return view('', [
            'detail' => ShopOrderLogic::getRefundDetail($id)
        ]);
    }

}