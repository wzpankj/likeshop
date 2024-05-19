<?php


namespace app\api\logic;

use app\common\basics\Logic;
use app\common\model\Cart;
use app\common\model\goods\GoodsCategory;
use app\common\logic\GoodsLogic as CommonGoodsLogic;
use app\common\server\UrlServer;
use think\facade\Event;

/**
 * 商品逻辑层
 * Class GoodsLogic
 * @package app\api\logic
 */
class GoodsLogic extends Logic
{
    /**
     * @notes 门店商品列表
     * @param $params
     * @param $page_no
     * @param $page_size
     * @return mixed
     * @author ljj
     * @date 2021/9/7 3:22 下午
     */
    public static function lists($params,$page_no,$page_size,$user_id)
    {
        //记录店铺访问量
        event('ShopStat', $params['shop_id']);

        $where[] = ['gc.del', '=', 0];
        $where[] = ['sg.status', '=', 1];
        $where[] = ['sg.shop_id', '=', $params['shop_id']];
        if (isset($params['category_id']) && !empty($params['category_id'])) {
            $where[] = ['gc.id', '=', $params['category_id']];
        }

        $lists = GoodsCategory::alias('gc')
            ->join('goods g', 'g.goods_category_id = gc.id')
            ->join('shop_goods sg', 'g.id = sg.goods_id')
            ->with(['goods' => function($query) use($params) {
                $query->alias('g')->join('shop_goods sg', 'g.id = sg.goods_id')->where(['sg.shop_id'=>$params['shop_id'],'sg.status'=>1])->order('sort','asc')->field('g.id,g.name,g.goods_category_id,g.image,g.remark,g.spec_type')->order(['g.sort'=>'asc','g.id'=>'desc']);
            }])
            ->field('gc.id,gc.name,gc.image')
            ->where($where)
            ->order(['gc.sort'=>'asc','gc.id'=>'desc'])
            ->group('gc.id')
            ->select()
            ->toArray();

        //购物车中的商品信息
        if ($user_id) {
            $cart_goods_info = CommonGoodsLogic::getCartGoodsInfo($user_id,$params['shop_id']);
        }


        foreach ($lists as &$list) {
            $goods_ids = array_column($list['goods'],'id');
            $goods_price = CommonGoodsLogic::getGoodsPrice($params['shop_id'],$goods_ids);
            $goods_spec = CommonGoodsLogic::getGoodsSpec($goods_ids);

            foreach ($list['goods'] as &$val) {
                //商品价格
                $val['price'] = array_values($goods_price['goods_item'][$val['id']])[0]['price'];

                //商品SKU
                $val['goods_item'] = array_values($goods_price['goods_item'][$val['id']]);
                //商品物料
                $val['goods_material'] = $goods_price['goods_material'][$val['id']] ?? [];

                //商品规格
                $val['goods_spec_list'] = $goods_spec[$val['id']];

                //购物车中的商品信息
                $val['cart_goods_num'] = $cart_goods_info[$val['id']]['num'] ?? 0;
                $val['cart_id'] = $cart_goods_info[$val['id']]['id'] ?? 0;
            }
        }

        return $lists;
    }

}
