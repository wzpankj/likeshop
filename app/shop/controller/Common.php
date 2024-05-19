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
namespace app\shop\controller;
use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\CommonLogic;
use app\shop\logic\goods\GoodsLogic;

class Common extends ShopBase{


    /**
     * @notes 选择门店商品
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/13 12:14
     */
    public function selectGoods(){

        if($this->request->isAjax()){
            $get = $this->request->get();
            $get['shop_id'] = $this->shop_id;
            $data = CommonLogic::getGoodsList($get);
            return JsonServer::success('',$data);
        }
        return view('',  [
            'category_list' => GoodsLogic::goodsCategoryLists(),
        ]);
    }
}