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


namespace app\shop\controller;


use app\shop\logic\AdminLogic;
use app\shop\validate\AdminPasswordValidate;
use app\shop\validate\AdminValidate;
use app\common\basics\ShopBase;
use app\common\model\shop\ShopRole;
use app\common\model\shop\ShopAdmin;
use app\common\server\JsonServer;

class Admin extends ShopBase
{
    /**
     * Notes: 列表
     * @author 段誉(2021/4/10 16:44)
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('获取成功', AdminLogic::lists($get, $this->shop_id));
        }
        return view('', ['role_lists' => (new ShopRole())->getRoleLists()]);
    }


    /**
     * Notes: 添加
     * @author 段誉(2021/4/10 16:44)
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['disable'] = isset($post['disable']) && $post['disable'] == 'on' ? 0 : 1;
            (new AdminValidate())->goCheck('add');
            if (AdminLogic::addAdmin($this->shop_id, $post)) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error(AdminLogic::getError() ?: '操作失败');
        }
        return view('', [
            'role_lists' => (new ShopRole())->getRoleLists(['shop_id' => $this->shop_id])
        ]);
    }

    /**
     * Notes: 编辑
     * @author 段誉(2021/4/10 16:45)
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit()
    {
        $id = $this->request->get('admin_id');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['disable'] = isset($post['disable']) && $post['disable'] == 'on' ? 0 : 1;
            (new AdminValidate())->goCheck('edit');
            if (AdminLogic::editAdmin($this->shop_id, $post)) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error(AdminLogic::getError() ?: '操作失败');
        }
        return view('', [
            'detail' => ShopAdmin::find($id),
            'role_lists' => (new ShopRole())->getRoleLists(['shop_id' => $this->shop_id])
        ]);
    }


    /**
     * Notes: 删除
     * @author 段誉(2021/4/14 15:25)
     * @return \think\response\Json
     */
    public function del()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('admin_id');
            if (AdminLogic::delAdmin($this->shop_id, $id)) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error(AdminLogic::getError() ?: '操作失败');
        }
    }



    /**
     * Notes: 修改密码
     * @author 段誉(2021/4/30 12:03)
     * @return \think\response\Json|\think\response\View
     */
    public function password()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['admin_id'] = $this->admin_id;
            (new AdminPasswordValidate())->goCheck('', $post);
            $res = AdminLogic::updatePassword($post['password'], $this->admin_id, $this->shop_id);
            if ($res) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error(AdminLogic::getError() ?: '系统错误');
        }
        return view();
    }

}