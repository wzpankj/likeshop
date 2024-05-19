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
namespace app\admin\controller\marketing;
use app\common\basics\AdminBase;
use app\common\model\shop\Shop;
use app\common\server\JsonServer;
use app\admin\logic\marketing\CouponLogic;

/**
 * 优惠券
 * Class Coupon
 * @package app\shop\controller\marketing
 */
class Coupon extends AdminBase {


    /**
     * @notes 优惠券列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/12 18:37
     */
    public function lists(){
        if($this->request->isAjax()){
            $get = $this->request->get();
            $lists = CouponLogic::lists($get,$this->page_no,$this->page_size);
            return JsonServer::success('',$lists);
        }
        return view('', [
            'shop_list' => Shop::where(['del'=>0])->column('name','id')
        ]);
    }


    /**
     * @notes 优惠券详情
     * @return \think\response\View
     * @author cjhao
     * @date 2022/1/13 17:55
     */
    public function detail(){
        $id = $this->request->get('id');
        $detail = CouponLogic::getCoupon($id,true);
        return view('', [
            'detail' => json_encode($detail, JSON_UNESCAPED_UNICODE)
        ]);
    }


    /**
     * @notes 优惠券发放记录
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2022/1/13 17:57
     */
    public function couponLog(){
        if($this->request->isAjax()){
            $get = $this->request->get();
            $data = CouponLogic::log($get);
            return JsonServer::success('', $data);
        }

        $id = $this->request->get('id');
        return view('', [
            'id' => $id
        ]);
    }



}