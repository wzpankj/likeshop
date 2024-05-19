<?php
namespace app\api\controller;

use app\api\validate\UserValidate;
use app\common\basics\Api;
use app\api\logic\UserLogic;
use app\common\enum\NoticeEnum;
use app\common\server\JsonServer;
use app\api\validate\UpdateUserValidate;
use app\api\validate\SetWechatUserValidate;
use app\api\validate\WechatMobileValidate;
use app\api\validate\ChangeMobileValidate;
use think\exception\ValidateException;

/**
 * 用户控制器
 * Class User
 * @package app\api\controller
 */
class User extends Api
{
    /***
     * 个人中心
     */
    public function center()
    {
        $config = UserLogic::center($this->user_id);
        return JsonServer::success('', $config);
    }

    /**
     * 用户信息
     */
    public function info()
    {
        return JsonServer::success('', UserLogic::getUserInfo($this->user_id));
    }

    /**
     * Notes:设置用户信息
     */
    public function setInfo()
    {
        $post = $this->request->post();
        (new UpdateUserValidate())->goCheck('set', $post);
        UserLogic::setUserInfo($post,$this->user_id);
        return JsonServer::success('设置成功');
    }

    /**
     * Notes:获取微信手机号
     */
    public function getMobile()
    {
        $post = $this->request->post();
        (new WechatMobileValidate())->goCheck($post);
        $result = UserLogic::getMobileByMnp($post);
        if($result === false) {
            return JsonServer::error(UserLogic::getError());
        }
        return JsonServer::success('', $result);
    }

    /**
     * Notes: 更换手机号 / 绑定手机号
     * @author 段誉(2021/6/23)
     * @return \think\response\Json
     */
    public function changeMobile()
    {
        $data = $this->request->post();
        $data['client'] = $this->client;
        $data['user_id'] = $this->user_id;
        if(isset($data['action']) && 'change' == $data['action']) {
            //变更手机号码
            $data['message_key'] = NoticeEnum::CHANGE_MOBILE_NOTICE;
            (new ChangeMobileValidate())->goCheck('', $data);
        } else {
            //绑定手机号码
            $data['message_key'] = NoticeEnum::BIND_MOBILE_NOTICE;
            (new ChangeMobileValidate())->goCheck('binding', $data);
        }
        $result = UserLogic::changeMobile($this->user_id, $data);
        return JsonServer::success('操作成功',$result);
    }

    /**
     * 余额明细
     */
    public function balanceDetails(){
        // 来源类型 0-全部 1-消费 2-充值
        $source = $this->request->get('source');
        (new UserValidate())->goCheck('balanceDetails');
        $data = UserLogic::balanceDetails($this->user_id, $source, $this->page_no, $this->page_size);
        return JsonServer::success('', $data);
    }

}