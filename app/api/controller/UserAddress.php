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


use app\api\logic\UserAddressLogic;
use app\api\validate\UserAddressValidate;
use app\common\{
    basics\Api,
    server\JsonServer
};


/**
 * 用户地址接口
 * Class UserAddress
 * @package app\api\controller
 */
class UserAddress extends Api
{
    public $like_not_need_login = ['handleregion'];

    //获取地址列表
    public function lists()
    {
        $user_id = $this->user_id;
        $result = UserAddressLogic::lists($user_id);
        return JsonServer::success('获取成功', $result);
    }

    //添加收货地址
    public function add()
    {
        $post = $this->request->post();
        (new UserAddressValidate())->goCheck('add',$post);
        $result = UserAddressLogic::addUserAddress($this->user_id, $post);
        if (true !== $result) {
            return JsonServer::error($result);
        }
        return JsonServer::success('添加成功');
    }

    //获取一条地址详情
    public function detail()
    {
        $get = $this->request->get();
        (new UserAddressValidate())->goCheck('detail',$get);
        $result = UserAddressLogic::getAddress($this->user_id, $get);
        return JsonServer::success('获取成功',$result);
    }

    //获取默认地址
    public function getDefault()
    {
        $result = UserAddressLogic::getDefaultAddress($this->user_id);
        return JsonServer::success('获取成功', $result);
    }

    //设置默认地址
    public function setDefault()
    {
        $post = $this->request->post();
        (new UserAddressValidate())->goCheck('set',$post);
        $result = UserAddressLogic::setDefaultAddress($this->user_id, $post);
        if (true !== $result) {
            return JsonServer::error('设置失败');
        }
        return JsonServer::success('设置成功');
    }

    //更新收货地址
    public function update()
    {
        $post = $this->request->post();
        (new UserAddressValidate())->goCheck('edit',$post);
        $result = UserAddressLogic::editUserAddress($this->user_id, $post);
        if (true !== $result) {
            return JsonServer::error($result);
        }
        return JsonServer::success('修改成功');
    }

    //删除收货地址
    public function del()
    {
        $post = $this->request->post();
        (new UserAddressValidate())->goCheck('del',$post);
        UserAddressLogic::delUserAddress($this->user_id, $post);
        return JsonServer::success('删除成功');
    }


    //将省市区名称转换成省市区id
    public function handleRegion()
    {
        $post = $this->request->post();
        (new UserAddressValidate())->goCheck('handleRegion', $post);
        $province = $this->request->post('province');
        $city = $this->request->post('city');
        $district = $this->request->post('district');
        $result = UserAddressLogic::handleRegion($province, $city, $district);
        return JsonServer::success('获取成功', $result);
    }

}