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

namespace app\shop\logic\index;

use app\common\basics\Logic;
use app\common\enum\OrderEnum;
use app\common\model\order\Order;
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
     * @notes 门店工作台基础数据
     * @param $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/26 5:55 下午
     */
    public static function stat($shop_id)
    {
        $day_order_amount = 0; //营业额
        $day_all_order = 0; //全部订单
        $day_shop_order = 0; //到店订单
        $day_takeout_order = 0; //外卖订单

        $lists = Order::where(['del'=>0,'shop_id'=>$shop_id,'pay_status'=>PayEnum::ISPAID])
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


    //图标数据 TODO
    public static function graphData($shop_id)
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

            $amount = Order::where([['shop_id','=',$shop_id],['create_time','between',[$start_now, $end_now]],['pay_status','=',PayEnum::ISPAID]])->sum('order_amount');
            $order = Order::where([['shop_id','=',$shop_id],['create_time','between',[$start_now, $end_now]],['pay_status','=',PayEnum::ISPAID]])->count('id');

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
     * @notes 获取门店当天订单数据
     * @param $admin_id
     * @return int
     * @author ljj
     * @date 2021/9/18 2:09 下午
     */
    public static function getDayOrder($admin_id)
    {
        $new = Order::where(['del'=>0,'shop_id'=>$admin_id])
            ->where(['pay_status'=>PayEnum::ISPAID,'order_status'=>OrderEnum::ORDER_STATUS_DELIVERY])
            ->whereDay('create_time')
            ->count();
        $old = Order::where(['del'=>0,'shop_id'=>$admin_id])
            ->where(['pay_status'=>PayEnum::ISPAID,'order_status'=>OrderEnum::ORDER_STATUS_DELIVERY])
            ->whereDay('create_time')
            ->whereTime('pay_time', '<=', '-5 second')
            ->count();
        if ($new > $old) {
            return 1;
        }
        return 0;
    }
}