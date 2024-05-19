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
namespace app\shop\controller\marketing;
use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\marketing\CouponLogic;
use app\shop\validate\marketing\CouponValidate;

/**
 * 优惠券
 * Class Coupon
 * @package app\shop\controller\marketing
 */
class Coupon extends ShopBase {


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
            $get['shop_id'] = $this->shop_id;
            $lists = CouponLogic::lists($get,$this->page_no,$this->page_size);
            return JsonServer::success('',$lists);
        }
        return view('', []);
    }


    /**
     * @notes 添加优惠券
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2022/1/13 14:29
     */
    public function add(){
        if($this->request->isAjax()){
            $post = (new CouponValidate())->goCheck('add');
            $post['shop_id'] = $this->shop_id;
            $result = CouponLogic::add($post);
            if($result === true) {
                return JsonServer::success('新增成功');
            }
            return JsonServer::error(CouponLogic::getError());
        }

        return view();
    }

    /**
     * @notes 编辑优惠券
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2022/1/13 17:28
     */
    public function edit(){
        if($this->request->isAjax()){
            $post = (new CouponValidate())->goCheck();
            $result = CouponLogic::edit($post);
            if($result === true) {
                return JsonServer::success('编辑成功');
            }
            return JsonServer::error(CouponLogic::getError());
        }
        $id = $this->request->get('id');
        $detail = CouponLogic::getCoupon($id,true);
        return view('', [
            'detail' => json_encode($detail, JSON_UNESCAPED_UNICODE)
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

    /**
     * @notes 上下架
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/1/13 18:00
     */
    public function changeStatus()
    {
        $post = $this->request->post();
        CouponLogic::changeStatus($post);
        return JsonServer::success('操作成功');

    }


    /**
     * @notes 删除优惠券
     * @return \think\response\Json
     * @author cjhao
     * @date 2022/1/13 18:21
     */
    public function del(){
        if($this->request->isAjax()){
            $id = $this->request->post('id');
            $result = CouponLogic::del($id);
            if(true === $result){
                return JsonServer::success('删除成功');
            }
            return JsonServer::error($result);

        }
    }

}