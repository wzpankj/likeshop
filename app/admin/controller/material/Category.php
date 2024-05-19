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

namespace app\admin\controller\material;


use app\admin\logic\material\CategoryLogic;
use app\admin\validate\material\CategoryValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;


/**
 * 平台物料分类控制器
 * Class Category
 * @package app\admin\controller\material
 */
class Category extends AdminBase
{
    /**
     * @notes 获取物料分类列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 3:37 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', CategoryLogic::lists($get));
        }
        return view();
    }

    /**
     * @notes 添加物料分类
     * @return \think\response\Json|\think\response\View
     * @author ljj
     * @date 2021/9/2 3:58 下午
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            (new CategoryValidate())->goCheck('add');

            $result = CategoryLogic::add($post);

            if (!$result) {
                return JsonServer::error(CategoryLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('操作成功');
        }
        return view();
    }

    /**
     * @notes 编辑物料分类
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 4:02 下午
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            (new CategoryValidate())->goCheck('edit');

            $result = CategoryLogic::edit($post);

            if (!$result) {
                return JsonServer::error(CategoryLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('操作成功');
        }

        $id = $this->request->get('id');
        return view('', [
            'detail' => CategoryLogic::detail($id),
        ]);
    }

    /**
     * @notes 删除物料分类
     * @return \think\response\Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 5:09 下午
     */
    public function del()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            (new CategoryValidate())->goCheck('del');

            $result = CategoryLogic::del($post);

            if (!$result) {
                return JsonServer::error(CategoryLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('操作成功');
        }
    }
}