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
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsItem;
use app\common\model\goods\GoodsCommentImage;
use app\common\model\shop\Shop;

/**
 * 平台商品评价-模型
 * Class GoodsComment
 * @package app\common\model\goods
 */
class GoodsComment extends Models
{
    /**
     * 关联商品
     */
    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id')
            ->field('id,name,image');
    }

    /**
     * 关联SKU
     */
    public function goodsItem()
    {
        return $this->hasOne(GoodsItem::class, 'id', 'item_id')
            ->field('id,image,spec_value_str');
    }

    /**
     * 关联图片评论
     */
    public function goodsCommentImage()
    {
        return $this->hasMany(GoodsCommentImage::class, 'goods_comment_id', 'id');
    }

    public function getStatusDescAttr($value)
    {
        return $value ? '显示' : '隐藏';
    }

    public function getGoodsCommentDescAttr($value)
    {
       $desc = [1=>'差评',2=>'差评',3=>'中评',4=>'好评',5=>'好评',];
       return $desc[$value];
    }
}