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


use app\admin\validate\SeckillTime;
use app\api\logic\SeckillLogic;
use app\common\basics\Models;
use app\common\enum\OrderEnum;
use app\api\logic\OrderLogic;
use app\common\model\seckill\SeckillGoods;
use app\common\model\seckill\SeckillTime as SeckillTimeModel;
use app\common\server\UrlServer;


/**
 * 平台商品规格-模型
 * Class GoodsItem
 * @package app\common\model\goods
 */
class GoodsItem extends Models
{
    /**
     * 根据goods_id,num和item_id计算价格
     */
    public function sumGoodsPrice($goods_id, $item_id, $num)
    {
        $goods_price = $this
            ->where([
                ['goods_id', '=', $goods_id],
                ['id', '=', $item_id],
            ])
            ->value('price');
        $seckill_goods_price = self::isSeckill($item_id);
        if($seckill_goods_price != 0){
            $goods_price = $seckill_goods_price;
            OrderLogic::$order_type = OrderEnum::SECKILL_ORDER;
        }
        $is_member = Goods::where('id',$goods_id)->value('is_member');
        if ($is_member === 0 || empty($is_member)){//不参与会员价
            $price = round($goods_price*$num,2);
        }
        if ($is_member == 1){//参与会员价-暂时统一不参与，延后了解详情之后修改
            $price = round($goods_price*$num,2);
        }
        return $price;
    }
    /***
     *
     *是否为秒杀商品
     *
    ***/
    public static function isSeckill($item_id){

        //当前时段秒杀商品
        $seckill = SeckillLogic::getSeckillGoods();
        $seckill_goods = $seckill['seckill_goods'];

        //当前商品规格是否为秒杀商品
        if (isset($seckill_goods[$item_id])) {
            return $seckill_goods[$item_id]['price'];
        }else{
            return 0;
        }
    }
}