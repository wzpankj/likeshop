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


namespace app\shop\controller\goods;


use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\goods\GoodsLogic;
use app\shop\validate\goods\GoodsValidate;


/**
 * 门店平台商品库控制器
 * Class Goods
 * @package app\shop\controller\goods
 */
class Goods extends ShopBase
{
    /**
     * @notes 平台商品列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 7:09 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', GoodsLogic::lists($get,$this->shop_id));
        }

        return view('', [
            'goods_category' => GoodsLogic::goodsCategoryLists(),
        ]);
    }

    /**
     * @notes 加入门店商品
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/31 10:25 上午
     */
    public function joinShopGoods()
    {
        $post = $this->request->post();
        //验证
        (new GoodsValidate())->goCheck('joinShopGoods');

        $result = GoodsLogic::joinShopGoods($post,$this->shop_id);
        if (true !== $result) {
            return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 移出门店商品
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/31 10:57 上午
     */
    public function removeShopGoods()
    {
        $post = $this->request->post();
        //验证
        (new GoodsValidate())->goCheck('removeShopGoods');

        $result = GoodsLogic::removeShopGoods($post,$this->shop_id);
        if (true !== $result) {
            return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/8/31 5:26 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',GoodsLogic::exportFile($get,$this->shop_id));
    }

}
