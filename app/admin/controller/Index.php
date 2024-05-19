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


namespace app\admin\controller;


use app\admin\logic\index\StatLogic;
use app\admin\server\MenuServer;
use app\common\basics\AdminBase;
use app\common\model\Role;
use app\common\server\ConfigServer;
use app\common\server\JsonServer;
use think\facade\Config;

class Index extends AdminBase
{
    /**
     * 后台前端全局界面
     * @return mixed
     */
    public function index()
    {
        return view('',[
            'config' => [
                'shop_name'         => ConfigServer::get('website', 'shop_name'),
                'web_favicon'       => ConfigServer::get('website', 'web_favicon'),
                'shop_logo'         => ConfigServer::get('website', 'shop_logo'),
            ],
            'menu' => MenuServer::getMenuTree($this->adminUser['role_id']), // 菜单渲染
            'view_app_trace' => Config::get('app.app_trace'), // 开启右上角前端示例
            'admin_name' => $this->adminUser['name'],//管理员名称
            'role_name' => (new Role())->getRoleName($this->adminUser['role_id']), // 角色名称
        ]);
    }

    /**
     * 工作台
     * @return mixed
     */
    public function stat()
    {
        if($this->request->isAjax()){
            return JsonServer::success('', StatLogic::graphData());
        }
        return view('', [
            'info' => StatLogic::stat()
        ]);
    }
    

    /**
     * 工作台商家数据
     * @return mixed
     */
    public function shop()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', StatLogic::shopLists($get));
        }
    }
}