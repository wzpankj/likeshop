<?php

namespace app\common\model\order;

use app\common\basics\Models;
use app\common\model\goods\GoodsItem;
use app\common\model\goods\Goods;
use app\common\model\shop\Shop;

/**
 * Class OrderGoods
 * @package app\common\model\order
 */
class OrderGoods extends Models
{
    //json转数组
    protected $json = ['goods_snap','material'];
    protected $jsonAssoc = true;

    /**
     * @notes 关联GoodsItem模型
     * @return \think\model\relation\HasOne
     * @author suny
     * @date 2021/7/13 6:46 下午
     */
    public function goodsItem()
    {

        return $this->hasOne(GoodsItem::class, 'id', 'item_id')
            ->field('id,image,spec_value_str');
    }

    /**
     * @notes 关联Shop模型
     * @return \think\model\relation\HasOne
     * @author suny
     * @date 2021/7/13 6:46 下午
     */
    public function shop()
    {

        return $this->hasOne(Shop::class, 'id', 'shop_id')
            ->append(['province_name','city_name','districtName'])
            ->field('id,name');
    }
}