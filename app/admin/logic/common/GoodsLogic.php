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
namespace app\admin\logic\common;
use app\common\basics\Logic;
use think\facade\Db;

class goodsLogic extends Logic{

    /**
     * Notes:获取商品列表
     * @param $get
     * @return array
     * @author: cjhao 2021/4/21 14:44
     */
    public static function selectGoods($get){
        $where[] = ['del','=',0];

        if(isset($get['keyword']) && $get['keyword']){
            $where[] = ['name','like','%'.$get['keyword'].'%'];
        }

        $lists = Db::name('goods')
                ->where($where)
                ->paginate(['list_rows'=>$get['limit'],'page'=>$get['page']]);

        $list = $lists->items();
        foreach ($list as $key => $goods){
            $price = $goods['min_price'].'~'.$goods['max_price'];

            if($goods['min_price'] !== $goods['max_price']){
                $price = $goods['min_price'];
            }

            $list[$key]['price'] = $price;
        }

        $count = $lists->total();

        return ['count'=>$count,'lists'=>$list];
    }
}