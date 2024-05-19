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


namespace app\shop\logic\goods;


use app\common\basics\Logic;
use app\common\enum\GoodsEnum;
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsCategory;
use app\common\model\goods\GoodsItem;
use app\common\model\GoodsMaterial;
use app\common\model\shop\ShopGoods;
use app\common\model\shop\ShopGoodsItem;
use app\common\model\ShopMaterial;


/**
 * 门店平台商品库-逻辑层
 * Class GoodsLogic
 * @package app\shop\logic\goods
 */
class GoodsLogic extends Logic
{
    /**
     * @notes 平台商品列表
     * @param $get
     * @return array
     * @author ljj
     * @date 2021/8/30 7:11 下午
     */
    public static function lists($get,$admin_id)
    {
        $shop_goods_ids = ShopGoods::where('shop_id',$admin_id)->column('goods_id');

        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.goods_category_id','=', $get['goods_category']];
        }
        //门店商品
        if (isset($get['is_shop_goods']) && $get['is_shop_goods'] == 1) {
            $where[] = ['g.id','in', $shop_goods_ids];
        }
        //非门店商品
        if (isset($get['is_shop_goods']) && $get['is_shop_goods'] == 2) {
            $where[] = ['g.id','notin', $shop_goods_ids];
        }


        $lists = Goods::alias('g')
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->field('g.id, g.image, g.name, gc.name as goods_category_name, g.min_price, g.max_price, g.status, g.sort')
            ->where($where)
            ->page($get['page'], $get['limit'])
            ->order(['g.sort'=>'asc','g.id'=>'desc'])
            ->select()
            ->toArray();

        $count = Goods::alias('g')->Join('goods_category gc', 'gc.id=g.goods_category_id')->where($where)->count();

        if(!$count) {
            return ['count' => $count, 'lists' => $lists];
        }

        foreach($lists as $key=>&$item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部可售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }

            //门店商品
            $item['is_shop_goods'] = 0;
            $item['is_shop_goods_desc'] = '否';
            if (in_array($item['id'], $shop_goods_ids)) {
                $item['is_shop_goods'] = 1;
                $item['is_shop_goods_desc'] = '是';
            }
        }

        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 获取商品分类列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 7:25 下午
     */
    public static function goodsCategoryLists()
    {
        return GoodsCategory::where('del',0)->field('id,name')->select()->toArray();
    }

    /**
     * @notes 加入门店商品
     * @param $post
     * @param $admin_id
     * @return bool
     * @author ljj
     * @date 2021/8/31 10:24 上午
     */
    public static function joinShopGoods($post,$admin_id)
    {
        try {
            //添加门店商品
            $shop_goods = new ShopGoods;
            $shop_goods_data = [];
            foreach ($post['goods_ids'] as $key=>$val) {
                $result = ShopGoods::where(['goods_id'=>$val,'shop_id'=>$admin_id])->findOrEmpty();
                if (!$result->isEmpty()) {
                    unset($post['goods_ids'][$key]);
                    continue;
                }
                $shop_goods_data[] = ['goods_id'=>$val,'shop_id'=>$admin_id];
            }
            $shop_goods->saveAll($shop_goods_data);

            //添加门店商品SKU
            $shop_goods_item = new ShopGoodsItem;
            $goods_item = GoodsItem::where('goods_id', 'in', implode (',',$post['goods_ids']))->field('id as item_id,goods_id,price')->select()->toArray();
            foreach ($goods_item as &$item) {
                $item['shop_goods_id'] = ShopGoods::where(['goods_id'=>$item['goods_id'],'shop_id'=>$admin_id])->value('id');
                $item['shop_id'] = $admin_id;
            }
            $shop_goods_item->saveAll($goods_item);

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 移出门店商品
     * @param $post
     * @param $admin_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/31 10:57 上午
     */
    public static function removeShopGoods($post,$admin_id)
    {
        //移除门店物料
        $material_ids = GoodsMaterial::where(['goods_id'=>$post['goods_ids']])->column('material_id');
        ShopMaterial::where(['material_id'=>$material_ids,'shop_id'=>$admin_id])->delete();

        //移除门店商品&移除门店商品SKU
        $data = ShopGoods::with(['shop_goods_item'])
            ->where('goods_id','in', implode(',',$post['goods_ids']))
            ->where('shop_id',$admin_id)
            ->select();
        foreach ($data as $model) {
            $model->together(['shop_goods_item'])->delete();
        }

        return true;
    }

    /**
     * @notes 导出列表
     * @param $get
     * @param $admin_id
     * @return array
     * @author ljj
     * @date 2021/8/31 5:26 下午
     */
    public static function exportFile($get,$admin_id)
    {
        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.goods_category_id','=', $get['goods_category']];
        }

        $lists = Goods::alias('g')
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->field('g.id, g.image, g.name, gc.name as goods_category_name, g.min_price, g.max_price, g.status, g.sort')
            ->where($where)
            ->order(['g.sort'=>'asc','g.create_time'=>'desc'])
            ->select();

        $shop_goods_ids = ShopGoods::where('shop_id',$admin_id)->column('goods_id');

        $exportTitle = ['商品名称', '商品分类', 'SKU最低价', 'SKU最高价', '门店商品', '总部商品状态', '排序'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部可售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }
            //门店商品
            $item['is_shop_goods'] = 0;
            $item['is_shop_goods_desc'] = '否';
            if (in_array($item['id'], $shop_goods_ids)) {
                $item['is_shop_goods'] = 1;
                $item['is_shop_goods_desc'] = '是';
            }
            //搜索
            if (!empty($get['is_shop_goods'])) {
                if ($item['is_shop_goods'] != $get['is_shop_goods']) {
                    continue;
                }
            }

            $exportData[] = [$item['name'], $item['goods_category_name'], $item['min_price'], $item['max_price'], $item['is_shop_goods_desc'], $item['status_desc'], $item['sort']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'商品列表'.date('Y-m-d H:i:s',time())];
    }

}
