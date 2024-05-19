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
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\api\logic\CartLogic;
use app\api\validate\CartValidate;
use app\common\basics\Api;
use app\common\server\JsonServer;


/**
 * 购物车接口
 * Class Cart
 * @package app\api\controller
 */
class Cart extends Api
{

    /**
     * @notes
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/6 10:16
     */
    public function lists()
    {
        $get = (new CartValidate())->goCheck('lists');
        $lists = CartLogic::lists($this->user_id,$get['shop_id']);
        return JsonServer::success('获取成功', $lists);
    }


    /**
     * @notes 添加购物车
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/6 10:25
     */
    public function add()
    {

        $post = (new CartValidate())->goCheck('add');
        $res = CartLogic::add($post, $this->user_id);
        if (true === $res) {
            return JsonServer::success('添加成功');
        }
        $errmsg=CartLogic::getError();
        if (strpos($errmsg, '必须选择') !== false) {
            return JsonServer::error($errmsg,[],9);
        }
        return JsonServer::error($errmsg);
    }


    /**
     * @notes 改变购物车数量
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/7 14:54
     */
    public function change()
    {
        $post = (new CartValidate())->goCheck('change', ['user_id' => $this->user_id]);
        $res = CartLogic::change($post['cart_id'], $post['num']);
        if (true === $res) {
            return JsonServer::success('修改成功');
        }
        return JsonServer::error(CartLogic::getError());
    }


    /**
     * @notes 删除购物车
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/7 15:59
     */
    public function del()
    {
        $post = (new CartValidate())->goCheck('del', ['user_id' => $this->user_id]);
        if (CartLogic::del($post['cart_ids'], $this->user_id)) {
            return JsonServer::success('删除成功');
        }
        return JsonServer::error('删除失败');
    }




}