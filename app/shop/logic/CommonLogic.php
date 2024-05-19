<?php
namespace  app\shop\logic;

use app\common\basics\Logic;
use app\common\enum\ShopGoodsEnum;
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsItem;
use app\common\server\UrlServer;
use app\common\enum\GoodsEnum;
use app\shop\controller\goods\ShopGoods;

class CommonLogic extends Logic
{
    /**
     * @notes 选择门店商品
     * @param $get
     * @return array
     * @author cjhao
     * @date 2022/1/13 12:12
     */
    public static function getGoodsList($get)
    {
        $where = [
            ['shop_id', '=', $get['shop_id']],
            ['del', '=', GoodsEnum::DEL_NORMAL], // 未删除
            ['G.status', '=', GoodsEnum::STATUS_SHELVES], // 上架中
            ['SG.status', '=', ShopGoodsEnum::STATUS_SHELVES] // 上架中
        ];
        if(!empty($get['keyword'])) {
            $where[] = ['name', 'like','%'. $get['keyword'].'%'];
        }
        if(!empty($get['cid'])) {
            $where[] = ['goods_category_id', '=', $get['cid']];
        }
        $lists = Goods::alias('G')
                ->join('shop_goods SG','G.id = SG.goods_id')
                ->where($where)
                ->page($get['page'],$get['limit'])
                ->field('G.*,SG.total_stock')
                ->select();

        $count = Goods::alias('G')
                ->join('shop_goods SG','G.id = SG.goods_id')
                ->where($where)
                ->count();

        foreach($lists as &$item) {
            $item['price'] = '￥'.$item['min_price'].'~'.'￥'.$item['max_price'];
            if($item['min_price'] == $item['max_price']){
                $item['price'] = '￥'.$item['min_price'];
            }
            $item['image'] = UrlServer::getFileUrl($item['image']);
        }

        return [
            'count' => $count,
            'lists' => $lists
        ];

    }


}