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

namespace app\shop\controller\printer;

use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\printer\PrinterLogic;
use app\shop\validate\printer\PrinterValidate;

/**
 * 打印机管理控制器
 * Class Printer
 * @package app\shop\controller\printer
 */
class Printer extends ShopBase
{
    /**
     * @notes 打印机列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 2:16 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            return JsonServer::success('', PrinterLogic::lists($this->shop_id));
        }

        return view();
    }

    /**
     * @notes 添加打印机
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 5:23 下午
     */
    public function add()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            $post['shop_id'] = $this->shop_id;
            (new PrinterValidate())->goCheck('add',$post);

            $result = PrinterLogic::add($post, $this->shop_id);
            if (true !== $result) {
                return JsonServer::error($result);
            }
            return JsonServer::success('添加成功');
        }

        return view('', [
            'type_list' => PrinterLogic::getConfigList($this->shop_id),
            'template_list' => PrinterLogic::getTemplateList($this->shop_id),
        ]);
    }

    /**
     * @notes 编辑打印机
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 5:40 下午
     */
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            $post['shop_id'] = $this->shop_id;
            (new PrinterValidate())->goCheck('edit',$post);

            $result = PrinterLogic::edit($post);
            if (true !== $result) {
                return JsonServer::error($result);
            }
            return JsonServer::success('修改成功');
        }

        $id = $this->request->get('id');
        return view('', [
            'type_list' => PrinterLogic::getConfigList($this->shop_id),
            'template_list' => PrinterLogic::getTemplateList($this->shop_id),
            'detail' => PrinterLogic::getDetail($id),
        ]);
    }

    /**
     * @notes 删除打印机
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/23 5:51 下午
     */
    public function del()
    {
        $post = $this->request->post();
        (new PrinterValidate())->goCheck('del',$post);

        $result = PrinterLogic::del($post['id']);
        if (true !== $result) {
            return JsonServer::error($result);
        }
        return JsonServer::success('删除成功');
    }

    /**
     * @notes 测试打印
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/23 6:04 下午
     */
    public function testPrint()
    {
        $post = $this->request->post();
        (new PrinterValidate())->goCheck('testPrint',$post);

        $result = PrinterLogic::testPrint($post['id']);
        if (true !== $result) {
            return JsonServer::error($result);
        }
        return JsonServer::success('打印成功');
    }
}