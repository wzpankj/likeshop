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
namespace app\api\controller;
use app\api\logic\CouponLogic;
use app\common\basics\Api;
use app\common\server\JsonServer;

/**
 * 优惠券控制器
 * Class Coupon
 * @package app\api\controller
 */
class Coupon extends Api{

    public $like_not_need_login = ['lists'];

    /**
     * @notes 优惠券列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/19 10:40
     */
    public function lists(){
        $shop_id = $this->request->get('shop_id');
        $list = CouponLogic::lists($shop_id,$this->user_id);
        return JsonServer::success('',$list);
    }

    /**
     * @notes 领取优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/1/19 10:50
     */
    public function getCoupon(){
        $coupon_id = $this->request->post('id');
        $result = CouponLogic::getCoupon($coupon_id,$this->user_id);
        if(true === $result){
            return JsonServer::success('领取成功');
        }
        return JsonServer::error($result);

    }


    /**
     * @notes 我的优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/1/19 11:58
     */
    public function MyCoupon(){
        $shop_id = $this->request->get('shop_id');
        $type = $this->request->get('type',1);
        $list = CouponLogic::myCoupon($shop_id,$type,$this->user_id);
        return JsonServer::success('',$list);
    }

    /**
     * @notes 获取下单优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/1/19 16:17
     */
    public function getOrderCoupon(){
        $shop_id = $this->request->get('shop_id');
        $result = CouponLogic::getOrderCoupon($shop_id,$this->user_id);
        if(is_array($result)){
            return JsonServer::success('',$result);
        }
        return JsonServer::error($result);
    }
}