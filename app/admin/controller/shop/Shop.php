<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\admin\controller\shop;
use app\admin\{logic\shop\ShopLogic, logic\shop\StoreLogic, validate\shop\ShopValidate};
use app\common\{basics\AdminBase, enum\ShopEnum, server\ConfigServer, server\JsonServer};

/**
 * 门店控制器
 * Class Shop
 * @package app\admin\controller\shop
 */
class Shop extends AdminBase
{

    /**
     * @notes 门店列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DbException
     * @author cjhao
     * @date 2021/8/30 10:00
     */
    public function lists()
    {
        if ($this->request->isAjax()){
            $get = $this->request->get();
            $lists = ShopLogic::lists($get);
            return JsonServer::success('获取成功', $lists);
        }
        return view('',['pricing_policy_list'=>ShopEnum::getPricingPriceDesc()]);
    }


    /**
     * @notes 添加门店
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/30 10:30
     */
    public function add()
    {
        if($this->request->isAjax()){
            $params['del'] = 0;
            $post = (new ShopValidate())->goCheck('add', $params);
            $result = ShopLogic::add($post);
            if (true === $result) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error($result);
        }
        return view('',['qq_map_key'=>ConfigServer::get('map','qq_map_key','')]);
    }

    /**
     * @notes 编辑门店
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/30 20:08
     */
    public function edit(){
        if($this->request->isAjax()){
            $params['del'] = 0;
            $post = (new ShopValidate())->goCheck('edit', $params);
            $result = ShopLogic::edit($post);
            if ($result) {
                return JsonServer::success('操作成功');
            }
            return JsonServer::error($result);
        }
        $id = $this->request->get('id');
        return view('', [
            'detail'    => ShopLogic::detail($id),
            'qq_map_key'=>ConfigServer::get('map','qq_map_key','')
        ]);
    }

    /**
     * @notes 单点登录
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/30 20:38
     */
    public function shopSso()
    {
        $id = $this->request->get('id', '');
        $data =  ShopLogic::shopSso($id);
        $this->redirect(url('shop/Login/adminSso',$data));
    }

    /**
     * @notes 修改账号
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/8/31 11:41
     */
    public function account()
    {
        if ($this->request->isAjax()) {
            $params['del'] = 0;
            (new ShopValidate())->goCheck('account',$params);

            $post = $this->request->post();
            if (!empty($post['password'])) {
                (new ShopValidate())->goCheck('pwd');
            }
            $res = ShopLogic::account($post);
            if ($res === false) {

            }
            return JsonServer::success('更新成功');
        }

        $id = $this->request->get('id');
        return view('', [
            'detail' => ShopLogic::getAccountInfo($id)
        ]);
    }

    /**
     * @notes 更新门店营业状态
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/8/31 15:55
     */
    public function updateStatus()
    {
        $post = (new ShopValidate())->goCheck('status');
        ShopLogic::updateStatus($post);
        return JsonServer::success('操作成功');
    }
}