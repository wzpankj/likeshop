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


namespace app\admin\logic\recharge_courtesy;

use app\common\basics\Logic;
use app\common\model\RechargeOrder;
use app\common\server\UrlServer;


/**
 * 平台充值记录-逻辑层
 * Class LogLogic
 * @package app\admin\logic\recharge_courtesy
 */
class LogLogic extends Logic
{
    /*
     * 商品统计
     */
    public static function statistics(){
        $where = [
            ['g.del', '=', GoodsEnum::DEL_NORMAL]
        ];
        $goods_list = Goods::alias('g')
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->where($where)
            ->field('g.status')
            ->select();

        $goods = [
            'all'      => 0,       //全部
            'upper' => 0,       //总部可售
            'lower'   => 0,       //总部停售
        ];
        foreach ($goods_list as $item){
            // 全部
            $goods['all']++;

            // 总部可售
            if($item['status'] == GoodsEnum::STATUS_SHELVES) {
                $goods['upper']++;
            }

            // 总部停售
            if($item['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                $goods['lower']++;
            }
        }

        return $goods;
    }

    /**
     * @notes
     * @param $get
     * @return array
     * @author heshihu
     * @date 2021/9/8 17:24
     */
    public static function lists($get)
    {
        $where = [];
        if(isset($get['order_sn']) && !($get['order_sn'] == '')) {
            $where[] = ['ro.order_sn','like','%'.$get['order_sn'].'%'];
        }
        if(isset($get['user_info']) && !($get['user_info'] == '')) {
            $where[] = ['u.nickname|u.sn','like','%'.$get['user_info'].'%'];
        }
        if(isset($get['pay_status'])) {
            $where[] = ['ro.pay_status','=',$get['pay_status']];
        }
        if(isset($get['create_time']) && !($get['create_time'] == '')) {
            $create_time_arr = explode(' 到 ',$get['create_time']);
            $where[] = ['ro.create_time','between',[strtotime($create_time_arr[0]),strtotime($create_time_arr[1])]];
        }


        $count = RechargeOrder::alias('ro')
            ->join('user u', 'u.id = ro.user_id')
            ->where($where)
            ->count();

        $lists = RechargeOrder::alias('ro')
            ->join('user u', 'u.id = ro.user_id')
            ->where($where)
            ->field('ro.*,u.nickname,u.sn,u.avatar')
            ->page($get['page'], $get['limit'])
            ->select()->toArray();

        foreach ($lists as &$log){
            $log['avatar'] = UrlServer::getFileUrl($log['avatar']);
            if(!$log['pay_time']){
                $log['pay_time'] = '-';
            }
            if(!$log['transaction_id']){
                $log['transaction_id'] = '-';
            }
        }
        return ['count' => $count, 'lists' => $lists];
    }

}
