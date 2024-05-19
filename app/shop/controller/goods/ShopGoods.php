<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\shop\controller\goods;


use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\goods\ShopGoodsLogic;
use app\shop\validate\goods\ShopGoodsValidate;

/**
 * 门店商品控制器
 * Class
 * @package
 */
class ShopGoods extends ShopBase
{
    /**
     * @notes 门店商品列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/1 11:53 上午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', ShopGoodsLogic::lists($get,$this->shop_id));
        }

        $cate_list = ShopGoodsLogic::goodsCategoryLists();
        $statistics = ShopGoodsLogic::statistics($this->shop_id);
        return view('', [
            'statistics' => $statistics,
            'goods_category' => $cate_list,
        ]);
    }

    /**
     * @notes 商品统计
     * @return \think\response\Json|void
     * @author ljj
     * @date 2021/9/1 2:21 下午
     */
    public function totalCount()
    {
        if ($this->request->isAjax()) {
            return JsonServer::success('获取成功', ShopGoodsLogic::statistics($this->shop_id));
        }
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/1 2:19 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',ShopGoodsLogic::exportFile($get,$this->shop_id));
    }

    /**
     * @notes 下架商品
     * @return \think\response\Json
     * @throws \Exception
     * @author ljj
     * @date 2021/9/1 2:30 下午
     */
    public function lower()
    {
        $post = $this->request->post();
        //验证
        (new ShopGoodsValidate())->goCheck('lower');

        // 修改商品状态
        $result = ShopGoodsLogic::lower($post);

        if (!$result) {
            return JsonServer::error(ShopGoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 上架商品
     * @return \think\response\Json
     * @throws \Exception
     * @author ljj
     * @date 2021/9/1 2:45 下午
     */
    public function upper()
    {
        $post = $this->request->post();
        //验证
        (new ShopGoodsValidate())->goCheck('upper');

        // 修改商品状态
        $result = ShopGoodsLogic::upper($post);

        if (!$result) {
            return JsonServer::error(ShopGoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 编辑商品
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/1 8:50 下午
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            //主表验证
            (new ShopGoodsValidate())->goCheck('edit');

            $result = ShopGoodsLogic::edit($post);
            if (true !== $result) {
                return JsonServer::error(ShopGoodsLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('编辑成功');
        }

        $goods_id = $this->request->get('goods_id');

        return view('', [
            'info' => ShopGoodsLogic::info($goods_id)
        ]);
    }
}