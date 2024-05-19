<?php
namespace app\admin\controller\user;
use app\common\{
    enum\ClientEnum,
    basics\AdminBase,
    server\JsonServer

};
use app\admin\{
    logic\user\UserLogic,
    validate\user\UserValidate
};

/**
 * 用户类
 * Class User
 * @package app\admin\controller\user
 */
class User extends  AdminBase
{
    /**
     * @notes 用户列表
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/9/22 14:20
     */
    public function lists(){
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', UserLogic::lists($get));
        }
        return view('', [
            'client_list' => ClientEnum::getClient()
        ]);
    }

    /**
     * @notes 用户编辑
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/9/22 14:21
     */
    public function edit(){
        if($this->request->isAjax()){
            $post = (new UserValidate())->goCheck('edit');
            UserLogic::edit($post);
            return JsonServer::success('编辑成功');

        }
        $id = $this->request->get('id', '', 'intval');
        $detail = UserLogic::getInfo($id);
        return view('', ['info' => $detail]);
    }

    /**
     * @notes 用户资料
     * @return \think\response\View
     * @author cjhao
     * @date 2021/9/22 15:48
     */
    public function info()
    {
        $get = (new UserValidate())->goCheck('info');
        $detail = UserLogic::getInfo($get['id']);
        return view('', ['detail' => $detail]);
    }

    /**
     * @notes 调整余额
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/9/22 14:22
     */
    public function adjustAccount(){
        if ($this->request->isAjax()) {
            $post = (new UserValidate())->goCheck('account');
            $result = UserLogic::adjustAccount($post);
            if(true === $result) {
                return JsonServer::success('调整成功');
            }
            return JsonServer::error($result);

        }
        $id = $this->request->get('id', '', 'intval');
        return view('', ['info' => UserLogic::getUser($id)]);
    }
}