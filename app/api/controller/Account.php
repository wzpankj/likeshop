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
namespace app\api\controller;

use app\api\logic\LoginLogic;
use app\common\basics\Api;
use app\common\enum\NoticeEnum;
use app\common\server\JsonServer;
use app\common\server\ConfigServer;
use app\api\validate\RegisterValidate;
use app\api\validate\LoginValidate;
use think\exception\ValidateException;
use app\api\validate\OaLoginValidate;

class Account extends Api
{

    public $like_not_need_login = ['silentLogin', 'authLogin', 'register', 'login','mnplogin', 'codeurl', 'oalogin', 'oplogin','logout','smslogin', 'uinAppLogin', 'silentLogin', 'authLogin'];

    /**
     * 注册
     */
    public function register()
    {
        $post  = $this->request->post();
        $post['check_code'] = ConfigServer::get('register', 'captcha', 0);
        $post['message_key'] = NoticeEnum::REGISTER_NOTICE;
        try{
            validate(RegisterValidate::class)->check($post);
        }catch(ValidateException $e) {
            return JsonServer::error($e->getError());
        }

        $result = LoginLogic::register($post);
        if($result !== false) {
            return JsonServer::success('注册成功', $result);
        }
        return JsonServer::error('注册失败:'.LoginLogic::getError());
    }


    //2021-0419  小程序新版登录调整
    /**
     * Notes: 小程序登录(旧系统用户,返回用户信息,否则返回空)
     * @author 段誉(2021/4/19 16:50)
     */
    public function silentLogin()
    {
        $post = $this->request->post();
        if (empty($post['code'])) {
            return JsonServer::error('参数缺失');
        }
        $data = LoginLogic::silentLogin($post);
        if(false === $data) {
            $error = LoginLogic::getError() ?? '登录失败';
            return JsonServer::error($error);
        }
        return JsonServer::success('', $data);
    }

    /**
     * Notes: 小程序登录(新用户登录->需要提交昵称和头像参数)
     * @author 段誉(2021/4/19 16:49)
     */
    public function authLogin()
    {
        $post = $this->request->post();
        if (empty($post['code'])
            || empty($post['headimgurl'])
            || empty($post['nickname']))
        {
            return JsonServer::error('参数缺失');
        }

        $data = LoginLogic::authLogin($post);
        if(false === $data) {
            $error = LoginLogic::getError() ?? '登录失败';
            return JsonServer::error($error);
        }
        return JsonServer::success('', $data);
    }

    /**
     * 手机号密码登录
     */
    public function login()
    {
        $post = $this->request->post();
        (new LoginValidate)->goCheck('mp_login', $post);
        $data = LoginLogic::mpLogin($post);
        return JsonServer::success('登录成功',$data);
    }

    /**
     * showdoc
     * @catalog 接口/账号
     * @title 获取获取向微信请求code的链接
     * @description
     * @method get
     * @url /account/codeurl
     * @param url 必填 varchar 前端当前url
     * @return_param url string codeurl
     * @remark 这里是备注信息
     * @number 0
     * @return  {"code":1,"msg":"获取成功","data":{"url":'http://mp.weixin……'}}
     */
    public function codeUrl()
    {
        $url = $this->request->get('url');
        return JsonServer::success('获取成功', ['url' => LoginLogic::codeUrl($url)]);
    }

    /**
     * showdoc
     * @catalog 接口/账号
     * @title 微信H5登录
     * @description 微信H5登录
     * @method post
     * @url /account/oalogin
     * @return {"code":1,"msg":"登录成功","data":["token":"3237676fa733d73333341",//登录令牌"nickname":"好象cms-小林",//昵称"avatar":"http://b2c.yixiangonline.com/uploads/user/avatar/3f102df244d5b40f21c4b25dc321c5ab.jpeg",//头像url"level":0,//等级],"show":0,"time":"0.775400"}
     * @param code 必填 string code
     * @return_param token string 登录令牌
     * @return_param nickname string 昵称
     * @return_param avatar string 头像
     * @remark
     * @number 1
     */
    public function oaLogin()
    {
        $post = $this->request->post();
        (new OaLoginValidate())->check($post);
        $data = LoginLogic::oaLogin($post);
        return JsonServer::success('登录成功', $data);
    }

    /**
     * Notes: uniapp微信登录
     */
    public function uinAppLogin()
    {
        $post = $this->request->post();
        $data = LoginLogic::uinAppLogin($post);
        if(is_string($data )){
            return JsonServer::error($data);
        }
        $data = [
            'code' => 1,
            'show' => 0,
            'msg' => '登录成功',
            'data' => $data
        ];
        return json($data);
    }

    /***
     * 短信登录
     * @return \think\response\Json
     */
    public function smsLogin()
    {
        $post = $this->request->post();
        (new LoginValidate())->goCheck('smsLogin');
        $data = LoginLogic::login($post);
        return JsonServer::success('登录成功', $data);
    }

    /***
     * 退出登录
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function logout()
    {
        LoginLogic::logout($this->user_id, $this->client);
        //退出登录只有成功
        return JsonServer::success();
    }
}