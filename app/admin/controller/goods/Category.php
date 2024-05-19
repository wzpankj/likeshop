<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\admin\controller\goods;

use app\common\basics\AdminBase;
use app\admin\logic\goods\CategoryLogic;
use app\admin\validate\goods\CategoryValidate;
use think\exception\ValidateException;
use app\common\server\JsonServer;
use think\facade\View;

/**
 * 平台商品分类控制器
 * Class Category
 * @package app\admin\controller\goods
 */
class Category extends AdminBase
{
    /**
     * 列表
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $category_tree = CategoryLogic::lists();
            // reqData方式渲染
            $treeTableData = [
                'code' => 0,
                'msg' => '分类列表',
                'data' => json_encode($category_tree)
            ];
            return json($treeTableData);
        }
        return view();
    }

    /**
     * @notes 添加商品
     * @return \think\response\Json|\think\response\View
     * @author ljj
     * @date 2021/8/30 5:26 下午
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            (new CategoryValidate())->goCheck('add');

            $res = CategoryLogic::add($post);
            if ($res) {
                return JsonServer::success('分类添加成功');
            } else {
                return JsonServer::error('分类添加失败');
            }
        }

        return view('add');
    }

    /**
     * @notes 编辑商品
     * @return \think\response\Json|\think\response\View
     * @author ljj
     * @date 2021/8/30 5:26 下午
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            (new CategoryValidate())->goCheck('edit');

            $res = CategoryLogic::edit($post);
            if ($res) {
                return JsonServer::success('编辑分类成功');
            } else {
                return JsonServer::error('编辑分类失败');
            }
        }

        $id = $this->request->get('id');
        $detail = CategoryLogic::getCategory($id);
        return view('edit', [
            'detail' => $detail,
        ]);
    }

    /**
     * 删除
     */
    public function del()
    {
        $post = $this->request->post();
        (new CategoryValidate())->goCheck('del');

        $res = CategoryLogic::del($post);
        if ($res) {
            return JsonServer::success('删除分类成功');
        } else {
            return JsonServer::error('删除分类失败');
        }
    }

}