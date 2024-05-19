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


use app\shop\logic\LoginLogic;
use app\shop\validate\LoginValidate;
use app\common\basics\ShopBase;
use app\common\server\JsonServer;

class Login extends ShopBase
{
    public $like_not_need_login = ['adminSso','login'];

    public function adminSso()
    {
        $params = $this->request->param();
        $result = LoginLogic::adminSso($params);
        if(true === $result){
            $this->redirect(url('index/index'));
        }
        $this->redirect(url('login/login'));

    }
    /**
     * Notes: 登录
     * @author FZR(2021/1/28 15:08)
     */
    public function login()
    {
        if ($this->request->isAjax()) {
            $post = request()->post();
            (new LoginValidate())->goCheck();
            if (LoginLogic::login($post)){
                return JsonServer::success('登录成功');
            }
            $error = LoginLogic::getError() ?: '登录失败';
            return JsonServer::error($error);
        }
        return view('', [
            'account'  => cookie('account'),
            'config'  => LoginLogic::config(),
        ]);
    }

    /**
     * Notes: 退出登录
     * @author FZR(2021/1/28 18:44)
     */
    public function logout()
    {
        LoginLogic::logout();
        $this->redirect(url('login/login'));
    }
}