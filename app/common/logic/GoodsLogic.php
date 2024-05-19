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
namespace app\common\logic;

use app\common\{enum\ShopEnum,
    enum\ShopGoodsEnum,
    model\Cart,
    model\goods\Goods,
    model\goods\GoodsSpec,
    model\Material,
    model\shop\Shop,
    model\shop\ShopGoods,
    model\ShopMaterial,
    model\goods\GoodsItem,
    model\shop\ShopGoodsItem,
    model\GoodsMaterialCategory};


/**
 * 商品逻辑类-公共方法
 * Class GoodsLogic
 * @package app\common\logic
 */
class GoodsLogic{

    /**
     * @notes 获取商品价格
     * @param int $shop_id 门店id
     * @param array $goods_ids 商品ids
     * @param boolean $is_all true-全部商品；false-上架商品
     * @return array
     * @author cjhao
     * @date 2021/9/3 11:01
     */
    public static function getGoodsPrice(int $shop_id,array $goods_ids,$is_all = true):array
    {
        //只找出上架商品
        if(false == $is_all){
            $goods_ids = ShopGoods::where(['goods_id'=>$goods_ids,'shop_id'=>$shop_id,'status'=>ShopGoodsEnum::STATUS_SHELVES])
                        ->column('goods_id');
        }

        $shop = Shop::where(['id'=>$shop_id,'del'=>0])
                ->withoutField('update_time,del')
                ->findOrEmpty()->toArray();

        $goods_item_list = GoodsItem::where(['goods_id'=>$goods_ids])
            ->column('*','id');

        $category_material_list = GoodsMaterialCategory::with(['material_list','category'])
                ->where(['goods_id'=>$goods_ids])
                ->select()->toArray();

        if($category_material_list){
            $all_materail_list = Material::where(['del'=>0])
                ->field('id,name,price')
                ->order('sort desc')
                ->select()->toArray();
        }


        $shop_materail_list = [];
        $goods_item = [];
        $material_list = [];
        //门店价格和库存
        $shop_goods_item = ShopGoodsItem::where(['goods_id'=>$goods_ids,'shop_id'=>$shop_id])
            ->column('price,stock','item_id');

        //门店定价
        if(ShopEnum::PRICING_POLICY_SHOP === $shop['pricing_policy']){

            $shop_materail_list = ShopMaterial::where(['shop_id'=>$shop_id])
                                ->column('price','material_id');

        }

        foreach ($goods_item_list as $id => $item){
            $shop_item_price = $shop_goods_item[$id]['price'] ?? -1;
            $stock = $shop_goods_item[$id]['stock'] ?? 0;
            $item['stock'] = $stock;
//            if($shop_item_price >= 0){
//                $item['price'] = $shop_item_price;
//            }
            //门店定价
            if(ShopEnum::PRICING_POLICY_SHOP === $shop['pricing_policy']){
                $item['price'] = $shop_item_price;
            }
            $goods_item[$item['goods_id']][$item['id']] = $item;

        }

        foreach ($category_material_list as $category_key => $category_material){
            $materail_ids = array_column($category_material['material_list'],'material_id');
            $materail_data = [];
            foreach ($all_materail_list as $materail){
                $shop_materail_price = $shop_materail_list[$materail['id']] ?? -1;
                if($shop_materail_price >= 0){
                    $materail['price'] = $shop_materail_price;
                }

                if(in_array($materail['id'],$materail_ids)){
                    $materail_data['category_id'] = $category_material['category_id'];
                    $materail_data['name']        = $category_material['name'];
                    $materail_data['goods_id']    = $category_material['goods_id'];
                    $materail_data['all_choice']  = $category_material['all_choice'];
                    $materail_data['must_select']  = $category_material['must_select'];
                    $materail_data['material'][]  = $materail;

                }
            }
            $material_list[$category_material['goods_id']][] = $materail_data;

        }

        return [
            'goods_item'        => $goods_item,
            'goods_material'    => $material_list,
        ];

    }


    /**
     * @notes 获取商品信息
     * @param int $shop_id
     * @param array $item_ids
     * @return array
     * @author cjhao
     * @date 2021/9/8 9:44
     */
    public static function getGoods(int $shop_id,array $goods_ids):array
    {

        $shop_goods = Goods::alias('G')
                ->join('shop_goods SG','SG.goods_id = G.id')
                ->where(['shop_id'=>$shop_id,'goods_id'=>$goods_ids])
                ->field('G.remark,G.spec_type,SG.status as shop_status,G.id,G.name,G.image,G.status')
                ->select()->toArray();

        $goods_ids = array_column($shop_goods,'id');
        $price_list = self::getGoodsPrice($shop_id,$goods_ids);

        return ['shop_goods'=>array_column($shop_goods,null,'id'),'price_list'=>$price_list];

    }

    /**
     * @notes 获取商品规格
     * @param $goods_ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/7 4:28 下午
     */
    public static function getGoodsSpec($goods_ids)
    {
        $lists = GoodsSpec::field('id,name,goods_id')
            ->with(['spec_value' => function($query) {
                $query->field('id,spec_id,value');
            }])
            ->where(['goods_id'=>$goods_ids])
            ->select()
            ->toArray();

        $group = [];
        foreach ($lists as $val) {
            $group[$val['goods_id']][] = $val;
        }

        return $group;
    }

    /**
     * @notes 获取购物车商品信息
     * @param $user_id
     * @param $shop_id
     * @return mixed
     * @author ljj
     * @date 2021/9/24 11:16 上午
     */
    public static function getCartGoodsInfo($user_id,$shop_id)
    {
        $lists = Cart::where(['user_id'=>$user_id,'shop_id'=>$shop_id])
            ->group('goods_id')
            ->column('id,sum(num) as num','goods_id');
        return $lists;
    }

    /**
     * @notes 获取某个商品的全部物料
     * @param $goods_material
     * @return array
     * @author cjhao
     * @date 2022/1/19 17:30
     */
    public static function getGoodsMaterial($goods_material){
        $goods_material_list = array_column($goods_material,'material');
        $goods_material_all = [];
        foreach ($goods_material_list as $goods_material){
            $goods_material_all = array_merge($goods_material_all,$goods_material);
        }
        return array_column($goods_material_all,null,'id');
    }


}