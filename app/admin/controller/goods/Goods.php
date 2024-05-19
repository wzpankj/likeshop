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


namespace app\admin\controller\goods;

use app\common\basics\AdminBase;
use app\common\server\ArrayServer;
use app\common\server\JsonServer;
use app\admin\logic\goods\GoodsLogic;
use app\admin\validate\goods\GoodsMoreSpec;
use app\admin\validate\goods\GoodsMoreSpecLists;
use app\admin\validate\goods\GoodsOneSpec;
use app\admin\validate\goods\GoodsValidate;

/**
 * 平台商品控制器
 * Class Goods
 * @package app\admin\controller\goods
 */
class Goods extends AdminBase
{
    /**
     * Notes: 列表
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', GoodsLogic::lists($get));
        }

        $cate_list = GoodsLogic::goodsCategoryLists();
        $statistics = GoodsLogic::statistics();
        return view('', [
            'statistics' => $statistics,
            'goods_category' => $cate_list,
        ]);
    }

    /**
     * @notes 商品统计
     * @return \think\response\Json|void
     * @author ljj
     * @date 2021/8/28 6:12 下午
     */
    public function totalCount()
    {
        if ($this->request->isAjax()) {
            return JsonServer::success('获取成功', GoodsLogic::statistics());
        }
    }

    /**
     * @notes 添加商品
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 2:19 下午
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            //主表验证
            (new GoodsValidate())->goCheck('add');

            //单规格验证
            if ($post['spec_type'] == 1) {
                (new GoodsOneSpec())->goCheck();
            }

            //多规格验证
            $spec_lists = [];
            if ($post['spec_type'] == 2) {
                $spec_lists = $post;
                unset($spec_lists['spec_id']);
                unset($spec_lists['spec_name']);
                unset($spec_lists['spec_values']);
                unset($spec_lists['spec_value_ids']);
                if (isset($spec_lists['material_category'])) {
                    unset($spec_lists['material_category']);
                }

                $spec_lists = ArrayServer::form_to_linear($spec_lists);

                //规格验证
                if (empty($spec_lists)) {
                    return JsonServer::error('至少添加一个规格');
                }
                // 规格项及规格值是否重复验证
                (new GoodsMoreSpec())->goCheck();

                //规格商品列表验证
                foreach ($spec_lists as $v) {
                    (new GoodsMoreSpecLists())->goCheck('', $v);
                }
            }

            // 添加商品
            $result = GoodsLogic::add($post, $spec_lists);

            if (true !== $result) {
                return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('添加成功');
        }

        return view('', [
            'goods_category_lists' => json_encode(GoodsLogic::goodsCategoryLists(), JSON_UNESCAPED_UNICODE),
            'material_category' => json_encode(GoodsLogic::getMaterialCategoryLists(), JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * @notes 修改商品状态
     * @return \think\response\Json|void
     * @throws \Exception
     * @author ljj
     * @date 2021/8/30 3:18 下午
     */
    public function changeStatus()
    {
        $post = $this->request->post();
        //验证
        (new GoodsValidate())->goCheck('status');

        // 修改商品状态
        $result = GoodsLogic::changeStatus($post);

        if (true !== $result) {
            return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }


    public function del()
    {
        $post = $this->request->post();
        
        $result = GoodsLogic::delStatus($post);

        if (true !== $result) {
            return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
        }
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 3:50 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',GoodsLogic::exportFile($get));
    }

    /**
     * @notes 编辑商品
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 4:49 下午
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            //主表验证
            (new GoodsValidate())->goCheck('edit');

            //单规格验证
            if ($post['spec_type'] == 1) {
                (new GoodsOneSpec())->goCheck();
            }

            //多规格验证
            $spec_lists = [];
            if ($post['spec_type'] == 2) {
                $spec_lists = $post;
                unset($spec_lists['spec_name']);
                unset($spec_lists['spec_values']);
                unset($spec_lists['spec_id']);
                unset($spec_lists['spec_value_ids']);
                if (isset($spec_lists['material_category'])) {
                    unset($spec_lists['material_category']);
                }

                $spec_lists = ArrayServer::form_to_linear($spec_lists);

                //规格验证
                if (empty($spec_lists)) {
                    return JsonServer::error('至少添加一个规格');
                }
                // 规格项验证
                (new GoodsMoreSpec())->goCheck();

                //规格商品列表验证
                foreach ($spec_lists as $v) {
                    (new GoodsMoreSpecLists())->goCheck('', $v);
                }
            }

            $result = GoodsLogic::edit($post, $spec_lists);
            if (true !== $result) {
                return JsonServer::error(GoodsLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('编辑成功');
        }

        $goods_id = $this->request->get('goods_id');

        return view('goods/goods/add', [
            'goods_category_lists' => json_encode(GoodsLogic::goodsCategoryLists(), JSON_UNESCAPED_UNICODE),
            'info' => json_encode(GoodsLogic::info($goods_id)['info'],JSON_UNESCAPED_UNICODE),
            'status' => GoodsLogic::info($goods_id)['status'],
            'material_category' => json_encode(GoodsLogic::getMaterialCategoryLists(), JSON_UNESCAPED_UNICODE),
            'goods_material' => json_encode(GoodsLogic::getGoodsMaterial($goods_id), JSON_UNESCAPED_UNICODE),
        ]);
    }
}