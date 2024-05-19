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


namespace app\admin\logic;


use app\common\basics\Logic;
use app\common\model\Admin;
use app\common\server\ConfigServer;
use think\facade\Cookie;

/**
 * 后台登录 逻辑
 * Class LoginLogic
 * @Author FZR
 * @package app\admin\logic
 */
class LoginLogic extends Logic
{

    /**
     * Notes: 登录
     * @param $post
     * @author 段誉(2021/4/10 10:40)
     * @return bool
     */
    public static function login($post)
    {
        $adminModel = new Admin();
        $admin_info = $adminModel
            ->field(['id','root', 'account', 'name', 'role_id'])
            ->where(['account' => $post['account'], 'del' => 0])
            ->findOrEmpty()->toArray();

        //session
        session('admin_info', $admin_info);

        //登录信息更新
        $adminModel->where(['account' => $post['account']])
            ->update([
                'login_ip' => request()->ip(),
                'login_time' => time()
            ]);

        //记住账号
        if (isset($post['remember_account']) && $post['remember_account'] == 'on') {
            Cookie::set('account', $post['account']);
        } else {
            Cookie::delete('account');
        }

        return true;
    }

    /**
     * Notes: 退出
     * @author 段誉(2021/4/10 10:40)
     */
    public static function logout()
    {
        session('admin_info', null);
    }



    public static function config()
    {
        $config = [
            'company_name' => ConfigServer::get('copyright', 'company_name'),
            'number' => ConfigServer::get('copyright', 'number'),
            'link' => ConfigServer::get('copyright', 'link'),

            'login_image' => ConfigServer::get('website_platform', 'platform_login_image'),
            'login_title' => ConfigServer::get('website_platform', 'platform_login_title'),

            'shop_name' => ConfigServer::get('website', 'shop_name'),
        ];
        return $config;
    }


}