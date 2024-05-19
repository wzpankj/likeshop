<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\admin\logic\index;

use app\common\basics\Logic;
use app\common\enum\OrderEnum;
use app\common\model\goods\Goods;
use app\common\model\order\Order;
use app\common\model\shop\Shop;
use app\common\server\UrlServer;
use app\common\enum\PayEnum;
use think\facade\Db;

/**
 * 工作台统计
 * Class StatLogic
 * @package app\admin\logic\index
 */
class StatLogic extends Logic
{
    /**
     * @notes 平台工作台基础数据
     * @param $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/26 5:55 下午
     */
    public static function stat()
    {
        $day_order_amount = 0; //营业额
        $day_all_order = 0; //全部订单
        $day_shop_order = 0; //到店订单
        $day_takeout_order = 0; //外卖订单

        $lists = Order::where(['del'=>0,'pay_status'=>PayEnum::ISPAID])
            ->whereDay('create_time')
            ->select()
            ->toArray();

        foreach ($lists as $list) {
            $day_all_order += 1;
            $day_order_amount += $list['order_amount'];
            if ($list['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $day_shop_order += 1;
            }
            if ($list['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                $day_takeout_order += 1;
            }
        }

        return [
            'time' => date('Y-m-d H:i:s', time()),
            'day_order_amount' => $day_order_amount,
            'day_all_order' => $day_all_order,
            'day_shop_order' => $day_shop_order,
            'day_takeout_order' => $day_takeout_order,
        ];
    }


    //图标数据 
    public static function graphData()
    {
        //当前时间戳
        $start_t = time();
        //echarts图表数据
        $echarts_order_amount = [];
        $echarts_order = [];
        $dates = [];
        for ($i = 15; $i >= 1; $i--) {
            $where_start = strtotime("- ".$i."day", $start_t);
            $dates[] = date('m-d',$where_start);
            $start_now = strtotime(date('Y-m-d',$where_start));
            $end_now = strtotime(date('Y-m-d 23:59:59',$where_start));

            $amount = Order::where([['create_time','between',[$start_now, $end_now]],['pay_status','=',PayEnum::ISPAID]])->sum('order_amount');
            $order = Order::where([['create_time','between',[$start_now, $end_now]],['pay_status','=',PayEnum::ISPAID]])->count('id');

            $echarts_order_amount[] = sprintf("%.2f",substr(sprintf("%.3f", $amount), 0, -2));
            $echarts_order[] = $order;
        }
        return [
            'echarts_order_amount'  => $echarts_order_amount,
            'echarts_order'    => $echarts_order,
            'dates'                 => $dates,
        ];
    }


    /**
     * @notes 排名信息
     * @param $get
     * @return array
     * @author ljj
     * @date 2021/9/27 2:22 下午
     */
    public static function shopLists($get)
    {
        $lists = [];

        if($get['type'] == 1){
            // 门店排名
            $lists = Order::alias('o')
                ->join('shop s','s.id = o.shop_id')
                ->where(['o.del'=>0,'o.pay_status'=>PayEnum::ISPAID])
                ->group('shop_id')
                ->limit(10)
                ->order(['sale_amount'=>'desc','s.id'=>'desc'])
                ->column('s.name,sum(o.order_amount) as sale_amount,count(o.id) as order_sum');

            foreach ($lists as &$shop) {
                $shop['sale_amount'] = '￥'.$shop['sale_amount'];
            }

        }else{
            // 商品排名
            $lists = goods::alias('g')
                ->join('order_goods og','g.id = og.goods_id')
                ->join('order o','o.id = og.order_id')
                ->where(['o.del'=>0,'o.pay_status'=>PayEnum::ISPAID])
                ->group('g.id')
                ->limit(10)
                ->order(['sale_amount'=>'desc','g.id'=>'desc'])
                ->column('g.code,g.name,sum(og.goods_price * og.goods_num) as sale_amount,sum(goods_num) as goods_num,count(o.id) as order_sum');

            foreach ($lists as &$goods) {
                $goods['name'] = $goods['code'].'-'.$goods['name'];
                $goods['sale_amount'] = '￥'.$goods['sale_amount'];
            }

        }

        return ['count'=>0,'lists'=>$lists];
    }
}