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


namespace app\api\logic;
use app\common\{
    model\Cart,
    basics\Logic,
    logic\GoodsLogic,
    enum\ShopGoodsEnum,
    model\shop\ShopGoods
};


/**
 * 购物车逻辑类
 * Class CartLogic
 * @package app\api\logic
 */
class CartLogic extends Logic
{

    /**
     * @notes 购物车列表
     * @param int $user_id
     * @param int $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/7 16:00
     */
    public static function lists(int $user_id, int $shop_id)
    {
        $cart_list = Cart::with(['goods'])
            ->where(['user_id'=>$user_id,'shop_id'=>$shop_id])
            ->field('id,goods_id,num,item_id,material_ids')
            ->select()->toArray();

        $goods_ids = array_column($cart_list,'goods_id');
        $goods_list = GoodsLogic::getGoodsPrice($shop_id,$goods_ids,false);
        $lists = [];
        $total_num = 0;
        $total_price = 0;

        foreach ($cart_list as $key => $cart){

            $goods_item = $goods_list['goods_item'][$cart['goods_id']] ?? [];
            $goods = $goods_item[$cart['item_id']] ?? [];
            if(empty($goods)){
                Cart::where(['user_id'=>$user_id,'goods_id'=>$cart['goods_id']])->delete();
                continue;
            }
            $goods_material = $goods_list['goods_material'][$cart['goods_id']] ?? [];
            $goods_price = $goods['price'];


            $goods_num = $cart['num'];
            $total_num += $goods_num;
            $total_price += $goods_price * $goods_num;

            $material_ids = [];
            $material_name = [];
            foreach ($goods_material as $material_list){
                $material_list = array_column($material_list['material'],null,'id');
                foreach ($cart['material_ids'] as $material_id){
                    $name = $material_list[$material_id]['name'] ?? '';
                    $material_price = $material_list[$material_id]['price'] ?? 0;
                    if($name){
                        $material_name[] = $name;
                        $material_ids[] = $material_id;
                        $goods_price += $material_price;
                        $total_price += $material_price * $goods_num;
                    }
                }

            }

            $lists[] = [
                'id'            => $cart['id'],
                'goods_id'      => $cart['goods_id'],
                'image'         => $cart['image'],
                'name'          => $cart['name'],
                'item_id'       => $cart['item_id'],
                'num'           => $cart['num'],
                'spec_value_str'=> $goods['spec_value_str'],
                'material_ids'  => $cart['material_ids'],
                'material_name' => $material_name,
//                'stock'         => $goods['stock'],
                'price'         => round($goods_price,2),
            ];

        }


        return [
            'lists'         => array_values($lists),
            'total_amount'  => round($total_price, 2),
            'total_num'     => $total_num,
        ];
    }


    /**
     * @notes 添加购物车
     * @param $post
     * @param $user_id
     * @return bool|string
     * @author cjhao
     * @date 2021/9/6 15:26
     */
    public static function add(array $post, int $user_id)
    {


        $shop_id = $post['shop_id'];
        $item_id = $post['item_id'];
        $num = $post['num'];
        $material_ids = $post['material_ids'] ?? [];
        $shop_goods = ShopGoods::alias('SG')
            ->join('ShopGoodsItem SGI','SG.id = SGI.shop_goods_id')
            ->where(['status'=>ShopGoodsEnum::STATUS_SHELVES,'SGI.item_id'=>$item_id])
            ->field('SGI.goods_id,SGI.item_id')
            ->find();

        if(empty($shop_goods)){
            self::$error =  '商品已停售';
            return false;
        }
        $goods_info = GoodsLogic::getGoodsPrice($shop_id,[$shop_goods->goods_id]);
        $goods_item = $goods_info['goods_item'][$shop_goods->goods_id][$shop_goods->item_id] ?? [];

        $stock = $goods_item['stock'] ?? 0;
//        $num = 1;
        $cart = Cart::where(['user_id' => $user_id, 'item_id' => $item_id,'shop_id'=>$shop_id])->findOrEmpty();
        //加上购物车的数量
        if(!$cart->isEmpty() && empty(array_diff($cart['material_ids'],$material_ids))){
            $num += $cart->num;
        }


        if($stock < $num){
            self::$error = '商品库存不足';
            return false;
        }

        if($material_ids){
            $materail_category_list = $goods_info['goods_material'][$shop_goods->goods_id] ?? [];
            $materail_list_ids = [];
            foreach ($materail_category_list as $materail_category){
                $materail_category_ids = array_column($materail_category['material'],'id');
                $materail_intersect = array_intersect($material_ids,$materail_category_ids);
                $materail_list_ids = array_merge($materail_category_ids,$materail_list_ids);
                // var_dump($materail_intersect);
                // var_dump($materail_category);
                //必选物料检查
                if(count($materail_intersect) == 0 && 1 == $materail_category['must_select']){
                    self::$error = $materail_category['name'].'必须选择';
                    return false;
                }

                //不支持多选的物料
                if(count($materail_intersect) >= 2 && 0 == $materail_category['all_choice']){
                    self::$error = $materail_category['name'].'选项不支持多选';
                    return false;
                }
            }
            $material_diff = array_diff($material_ids,$materail_list_ids);
            //物料信息错误
            if($material_diff){
                self::$error = '商品信息错误';
                return false;
            }
        }else{//else 二开的 目的为了检查没有选择必选物料 0个选择分支
            $materail_category_list = $goods_info['goods_material'][$shop_goods->goods_id] ?? [];
            $materail_list_ids = [];
            
            // exit;
            foreach ($materail_category_list as $materail_category){
                $materail_category_ids = array_column($materail_category['material'],'id');
                $materail_intersect = array_intersect($material_ids,$materail_category_ids);
                $materail_list_ids = array_merge($materail_category_ids,$materail_list_ids);
                // var_dump($materail_category);
                //必选物料检查
                if(count($materail_intersect) == 0 && 1 == $materail_category['must_select']){
                    self::$error = $materail_category['name'].'必须选择';
                    return false;
                }
            }
            
        }
        
        //购物车内已有该商品
        if(!$cart->isEmpty() && empty(array_diff($cart['material_ids'],$material_ids))){

            $cart->num = $num;
            $cart->save();

        }else{
            $cart = new Cart();
            $cart->user_id      = $user_id;
            $cart->shop_id      = $shop_id;
            $cart->goods_id     = $shop_goods->goods_id;
            $cart->item_id      = $item_id;
            $cart->num          = $num;
            $cart->material_ids = $material_ids;
            $cart->save();
        }


        return true;

    }



    /**
     * @notes 更新购物车数量
     * @param $cart_id
     * @param $num
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/7 15:59
     */
    public static function change(int $cart_id, int $num)
    {
        $cart = Cart::find($cart_id);
        $shop_goods = ShopGoods::alias('SG')
            ->join('ShopGoodsItem SGI','SG.id = SGI.shop_goods_id')
            ->where(['status'=>ShopGoodsEnum::STATUS_SHELVES,'SGI.item_id'=>$cart->item_id])
            ->field('SGI.goods_id,SGI.item_id')
            ->find();

        if(empty($shop_goods)){
            self::$error =  '商品已停售';
            return false;
        }
        $goods_info = GoodsLogic::getGoodsPrice($cart->shop_id,[$shop_goods['goods_id']]);
        $goods_item = $goods_info['goods_item'][$shop_goods['goods_id']][$shop_goods['item_id']] ?? [];
        $stock = $goods_item['stock'] ?? 0;
        if($stock < $num){
            self::$error = '商品库存不足';
            return false;
        }
        $cart->num = $num;
        $cart->save();
        return true;
    }


    /**
     * @notes 删除购物车
     * @param array $cart_id
     * @param int $user_id
     * @return bool
     * @author cjhao
     * @date 2021/9/7 16:03
     */
    public static function del(array $cart_id, int $user_id)
    {
        return Cart::where(['id' => $cart_id, 'user_id' => $user_id])->delete();
    }


    /**
     * @notes 获取购物车商品价格 数据格式：[goods_id=>商品价格]
     * @param $cart_list
     * @author cjhao
     * @date 2022/1/19 17:45
     */
    public static function getCartGoodsPrice($cart_list){
        $cart_goods_list = [];

        if(empty($cart_list)){
            return $cart_goods_list;
        }
        $goods_ids = array_column($cart_list,'goods_id');
        $shop_id = array_column($cart_list,'shop_id');
        $shop_goods_list = GoodsLogic::getGoodsPrice($shop_id[0],$goods_ids);
        $goods_item_list = $shop_goods_list['goods_item'];
        $goods_material_list = $shop_goods_list['goods_material'];

        //获取购物车的商品，组装数据 ['goods_id'=>'商品价格']
        foreach ($cart_list as $cart) {

            $goods_item = $goods_item_list[$cart['goods_id']][$cart['item_id']] ?? [];
            $goods_material = $goods_material_list[$cart['goods_id']] ?? [];

            $goods_material = GoodsLogic::getGoodsMaterial($goods_material);

            if(empty($goods_item)){
                continue;
            }
            $goods_item_price = $goods_item['price'];
            $goods_material_price = 0;
            if($cart['material_ids']){
                foreach ($cart['material_ids'] as $material_id){
                    $goods_material_price += $goods_material[$material_id]['price'] ?? 0;
                }
            }
            $total_price = round(($goods_item_price+$goods_material_price) * $cart['num'],2);

            $cart_goods_price = $cart_goods_list[$cart['goods_id']] ?? 0;
            $cart_goods_list[$cart['goods_id']] = $cart_goods_price + $total_price;
        }
        return $cart_goods_list;
    }


}