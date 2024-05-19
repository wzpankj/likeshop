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


namespace app\admin\logic;


use app\common\basics\Logic;
use app\common\server\UrlServer;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use think\facade\Db;

class StatisticsLogic extends Logic
{

    /**
     * Notes: 访问分析
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function visit($post)
    {
        //获取今天的时间戳 
        $today = strtotime('today');
        //近七天的开始日期
        $start_time = $today - 86400 * 6;
        //近七天的结束日期
        $end_time = $today;

        if (isset($post['start_time']) && $post['start_time'] && isset($post['end_time']) && $post['end_time']) {
            $start_time = strtotime($post['start_time']);
            $end_time   = strtotime($post['end_time']);
        }

        $user_count = Db::name('shop_stat')
            ->where([['create_time', 'between', [$start_time, $end_time]]])
            ->count('id');
            
        //当前时间戳
        $start_t = time();
        //echarts图表数据
        $echarts_count = [];
        $echarts_add = [];
        $dates = [];
        for ($i = 14; $i >= 0; $i--) {
            $where_start = strtotime("- " . $i . "day", $start_t);
            $dates[] = date('m-d', $where_start);
            $start_now = strtotime(date('Y-m-d', $where_start));
            $end_now = strtotime(date('Y-m-d 23:59:59', $where_start));
            $add = Db::name('shop_stat')
                ->where([['create_time', 'between', [$start_now, $end_now]]])
                ->count('id');
            $echarts_count[] = 0;
            $echarts_add[] = $add;
        }
//        dd($user_count,$echarts_add,$dates);

        return [
            'user_count'      => $user_count,
            'start_time'      => date('Y-m-d H:i:s', $start_time),
            'end_time'        => date('Y-m-d 23:59:59', $end_time),
            'echarts_count'   => $echarts_count,
            'echarts_add'     => $echarts_add,
            'days'            => $dates,
        ];
    }

    /**
     * Notes: 用户分析
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function member($post)
    {
        //获取今天的时间戳 
        $today = strtotime('today');
        //近七天的开始日期
        $start_time = $today - 86400 * 6;
        //近七天的结束日期
        $end_time = $today;

        if (isset($post['start_time']) && $post['start_time'] && isset($post['end_time']) && $post['end_time']) {
            $start_time = strtotime($post['start_time']);
            $end_time   = strtotime($post['end_time']);
        }
        $user_num = Db::name('user')
            ->count('id');

        $user_add = Db::name('user')
            ->where([['create_time', 'between', [$start_time, $end_time]]])
            ->count('id');

        //当前时间戳
        $start_t = time();
        //echarts图表数据
        $echarts_count = [];
        $echarts_add = [];
        $dates = [];
        for ($i = 14; $i >= 0; $i--) {
            $where_start = strtotime("- " . $i . "day", $start_t);
            $dates[] = date('m-d', $where_start);
            $start_now = strtotime(date('Y-m-d', $where_start));
            $end_now = strtotime(date('Y-m-d 23:59:59', $where_start));
            $add = Db::name('user')
                ->where([['create_time', 'between', [$start_now, $end_now]]])
                ->count('id');
            $echarts_count[] = 0;
            $echarts_add[] = $add;
        }

        return [
            'user_num'        => $user_num,
            'user_add'        => $user_add,
            'start_time'      => date('Y-m-d H:i:s', $start_time),
            'end_time'        => date('Y-m-d 23:59:59', $end_time),
            'echarts_count'   => $echarts_count,
            'echarts_add'     => $echarts_add,
            'days'            => $dates,
        ];
    }

    /**
     * Notes: 交易分析
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function trading($post)
    {
        //获取今天的时间戳 
        $today = strtotime('today');
        //近七天的开始日期
        $start_time = $today - 86400 * 6;
        //近七天的结束日期
        $end_time = $today;

        if (isset($post['start_time']) && $post['start_time'] && isset($post['end_time']) && $post['end_time']) {
            $start_time = strtotime($post['start_time']);
            $end_time   = strtotime($post['end_time']);
        }
        //数据汇总
        $order_num = Db::name('order')
            ->where([['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->count('id');
        $order_one_num = Db::name('order')
            ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_YOUSELF_TAKE], ['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->count('id');
        $order_two_num = Db::name('order')
            ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_TAKE_AWAY], ['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->count('id');
        $order_amount = Db::name('order')
            ->where([['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->sum('order_amount');
        $order_amount_one = Db::name('order')
            ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_YOUSELF_TAKE], ['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->sum('order_amount');
        $order_amount_two = Db::name('order')
            ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_TAKE_AWAY], ['create_time', 'between', [$start_time, $end_time]], ['pay_status', '>', PayEnum::UNPAID]])
            ->sum('order_amount');

        //当前时间戳
        $start_t = time();
        //echarts图表数据
        $echarts_count = [];
        $echarts_order_num_add = [];
        $echarts_order_amount_add = [];
        $dates = [];
        for ($i = 14; $i >= 0; $i--) {
            $where_start = strtotime("- " . $i . "day", $start_t);
            $dates[] = date('m-d', $where_start);
            $start_now = strtotime(date('Y-m-d', $where_start));
            $end_now = strtotime(date('Y-m-d 23:59:59', $where_start));
            $order_num_add = Db::name('order')
                ->where([['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->count('id');
            $order_one_num_add = Db::name('order')
                ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_YOUSELF_TAKE], ['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->count('id');
            $order_two_num_add = Db::name('order')
                ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_TAKE_AWAY], ['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->count('id');

            $order_amount_add = Db::name('order')
                ->where([['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->sum('order_amount');
            $order_amount_one_add = Db::name('order')
                ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_YOUSELF_TAKE], ['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->sum('order_amount');
            $order_amount_two_add = Db::name('order')
                ->where([['order_type', '=' , OrderEnum::ORDER_TYPE_TAKE_AWAY], ['create_time', 'between', [$start_now, $end_now]], ['pay_status', '>', PayEnum::UNPAID]])
                ->sum('order_amount');

            $echarts_count[] = 0;
            $echarts_order_num_add[] = $order_num_add;
            $echarts_order_one_num_add[] = $order_one_num_add;
            $echarts_order_two_num_add[] = $order_two_num_add;
            $echarts_order_amount_add[] = sprintf("%.2f",substr(sprintf("%.3f", $order_amount_add), 0, -2));
            $echarts_order_amount_one_add[] = sprintf("%.2f",substr(sprintf("%.3f", $order_amount_one_add), 0, -2));
            $echarts_order_amount_two_add[] = sprintf("%.2f",substr(sprintf("%.3f", $order_amount_two_add), 0, -2));
        }

        return [
            'order_num'                        => $order_num,
            'order_one_num'                    => $order_one_num,
            'order_two_num'                    => $order_two_num,
            'order_amount'                     => '￥'.number_format($order_amount,2),
            'order_amount_one'                 => '￥'.number_format($order_amount_one,2),
            'order_amount_two'                 => '￥'.number_format($order_amount_two,2),
            'start_time'                       => date('Y-m-d H:i:s', $start_time),
            'end_time'                         => date('Y-m-d 23:59:59', $end_time),
            'echarts_count'                    => $echarts_count,
            'echarts_order_num_add'            => $echarts_order_num_add,
            'echarts_order_one_num_add'        => $echarts_order_one_num_add,
            'echarts_order_two_num_add'        => $echarts_order_two_num_add,
            'echarts_order_amount_add'         => $echarts_order_amount_add,
            'echarts_order_amount_one_add'     => $echarts_order_amount_one_add,
            'echarts_order_amount_two_add'     => $echarts_order_amount_two_add,
            'days'                             => $dates,
        ];
    }

    /**
     * Notes: 门店分析
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function shop($get)
    {
        if (!isset($get['search_key'])) {
            $get['search_key'] = 'sales_price';
        }

        // 商家列表
        $shop_count = Db::name('shop')
            ->count();

        $shop_list = Db::name('shop')
            ->page($get['page'], $get['limit'])
            ->column('id,name');
        $shop_ids = array_column($shop_list, 'id');

        $sales_price_list = Db::name('order')
            ->where([['shop_id', 'in', $shop_ids], ['pay_status', '>', PayEnum::UNPAID]])
            ->group('shop_id')
            ->column('sum(order_amount) as sales_price', 'shop_id');

        $shop_stat_list = Db::name('shop_stat')
                ->where(['shop_id'=>$shop_ids])
                ->group('shop_id')
                ->column('count(id) as num','shop_id');

        foreach ($shop_list as $k => $shop) {
            $shop_list[$k]['sales_price'] = '￥0';
            $shop_list[$k]['stat_num'] = 0;

            if (isset($sales_price_list[$shop['id']])) {
                $shop_list[$k]['sales_price'] =  '￥' . number_format($sales_price_list[$shop['id']], 2);
            }
            if (isset($shop_stat_list[$shop['id']])) {
                $shop_list[$k]['stat_num'] = $shop_stat_list[$shop['id']];
            }
        }
        //排序
        $sort_field = array_column($shop_list, $get['search_key']);
        array_multisort($sort_field, SORT_DESC, $shop_list);

        foreach ($shop_list as $k => $shop) {
            $shop_list[$k]['number'] = $k + 1;
        }

        return ['count' => $shop_count, 'lists' => $shop_list];
    }

    /**
     * Notes: 商品分析
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function goods($get)
    {
        if (!isset($get['search_key'])) {
            $get['search_key'] = 'sales_volume';
        }

        // 商品列表      
        $goods_count = Db::name('order')->alias('o')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->where([['o.pay_status', '=', 1]])
            ->group('og.goods_id')
            ->count();

        $goods_list = Db::name('order')->alias('o')
            ->join('order_goods og', 'og.order_id = o.id')
            ->join('goods g', 'g.id = og.goods_id')
            ->where([['o.pay_status', '=', 1]])
            ->group('og.goods_id')
            ->page($get['page'], $get['limit'])
            ->order($get['search_key'].' desc')
            ->field('count(o.id) as sales_volume,sum(o.order_amount) as sales_price,g.image,g.code,g.name as goods_name')
            ->select()->toArray();

        foreach ($goods_list as $k => $item) {
            $goods_list[$k]['number'] = $k + 1;
            $goods_list[$k]['sales_price'] = '￥' . number_format($item['sales_price'], 2);
            $goods_list[$k]['goods_image'] = UrlServer::getFileUrl($item['image']);
        }

        return ['count' => $goods_count, 'lists' => $goods_list];
    }
}
