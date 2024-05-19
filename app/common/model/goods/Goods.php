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


namespace app\common\model\goods;

use app\common\basics\Models;
use app\common\model\GoodsMaterial;
use app\common\model\GoodsMaterialCategory;
use app\common\model\shop\ShopGoods;


/**
 * 平台商品-模型
 * Class Goods
 * @package app\common\model\goods
 */
class Goods extends Models
{
    /**
     * 商品SKU 关联模型
     */
    public function GoodsItem()
    {
        return $this->hasMany(GoodsItem::class, 'goods_id', 'id')
            ->field('id, goods_id, spec_value_ids, spec_value_str, market_price, price');
    }

    /**
     * @notes 商品物料 关联模型
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2021/9/6 3:46 下午
     */
    public function goodsMaterial()
    {
        return $this->hasMany(GoodsMaterial::class, 'goods_id', 'id');
    }

    /**
     * @notes 商品物料分类 关联模型
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2021/9/6 3:47 下午
     */
    public function goodsMaterialCategory()
    {
        return $this->hasMany(GoodsMaterialCategory::class, 'goods_id', 'id');
    }

    /**
     * @notes 门店商品 关联模型
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2021/9/7 2:32 下午
     */
    public function shopGoods()
    {
        return $this->hasMany(ShopGoods::class, 'goods_id', 'id');
    }

    /**
     * 根据商品id获取商品名称
     */
    public function getGoodsNameById($goods_id)
    {
        return $this->where('id',$goods_id)->value('name');

    }

    /**
     * 根据商品id查询商品是否上架
     */
    public function checkStatusById($goods_id)
    {
        $status = $this
            ->where([
                ['id','=',$goods_id],
                ['del','=',0],
            ])
            ->value('status');
        if ($status){
            if ($status == 1){
                return true;
            }
            if (empty($status) || $status ===0){
                return  false;
            }
        }
        return false;
    }


    /**
     * 根据goods_id查询商品配送方式及所需信息
     */
    public function getExpressType($goods_id)
    {
        return $this->where('id',$goods_id)->column('express_type,express_money,express_template_id')[0];
    }

    /**
     * 最小值与最大值范围
     */
    public function getMinMaxPriceAttr($value, $data)
    {
        return '¥ ' . $data['min_price'] . '~ ¥ '. $data['max_price'];
    }

}