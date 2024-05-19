<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\admin\logic\marketing;
use app\common\basics\Logic;
use app\common\enum\CouponEnum;
use app\common\enum\GoodsEnum;
use app\common\enum\ShopGoodsEnum;
use app\common\model\goods\Goods;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;
use app\common\server\UrlServer;

class CouponLogic extends Logic{

    /**
     * @notes 优惠券列表
     * @param $get
     * @param $page_no
     * @param $page_size
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/12 18:37
     */
    public static function lists($get,$page_no,$page_size){
        \app\common\logic\CouponLogic::updateOverCoupon();
        $where[] = ['C.del','=',0];

        if($get['type']){
            $where[] = ['C.status','=',$get['type']];
        }
        if(isset($get['name']) && $get['name']){
            $where[] = ['C.name','like','%'.$get['name'].'%'];
        }
        if(isset($get['get_type']) && $get['get_type']){
            $where[] = ['C.get_type','=',$get['get_type']];
        }
        if(isset($get['create_time']) && $get['create_time']){
            $where[] = ['C.create_time','>=',$get['create_time']];
        }
        if(isset($get['end_time']) && $get['end_time']){
            $where[] = ['C.create_time','<=',$get['end_time']];
        }
        if(isset($get['shop']) && $get['shop']){
            $where[] = ['S.name|S.shop_sn','like','%'.$get['shop'].'%'];
        }

        $count = Coupon::alias('C')
                ->join('shop S','C.shop_id = S.id')
                ->where($where)
                ->count();

        $lists = Coupon::alias('C')
                ->join('shop S','C.shop_id = S.id')
                ->where($where)
                ->page($page_no,$page_size)
                ->field('C.*,S.shop_sn,S.name as shop_name')
                ->order('C.id desc')
                ->select();
        foreach ($lists as $coupon){
            \app\common\logic\CouponLogic::getCouponInfo($coupon);
        }


        $more = is_more($count,$page_no,$page_size);

        return [
            'count'         => $count,
            'list'          => $lists,
            'more'          => $more,
            'statistics'    => self::couponStatistics($get),
        ];

    }





    /**
     * @notes 获取优惠券信息
     * @param $id
     * @param bool $get_data
     * @return array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/18 17:10
     */
    public static function getCoupon($id,$get_data = false){
        $coupon = Coupon::find($id);
        $coupon['goods_coupon'] = [];
        if($get_data) {
            $coupon = $coupon->getData();
            if($coupon['use_goods_type'] != 1){ // 非全部商品
                $where = [
                    ['shop_id', '=', $coupon['shop_id']],
                    ['CG.coupon_id', '=', $coupon['id']],
                    ['del', '=', GoodsEnum::DEL_NORMAL], // 未删除
                    ['G.status', '=', GoodsEnum::STATUS_SHELVES], // 上架中
                    ['SG.status', '=', ShopGoodsEnum::STATUS_SHELVES] // 上架中
                ];
                $goods_coupon = Goods::alias('G')
                    ->join('coupon_goods CG','CG.goods_id = G.id')
                    ->join('shop_goods SG','G.id = SG.goods_id')
                    ->where($where)
                    ->field('G.*,SG.total_stock')
                    ->select();

                foreach ($goods_coupon as &$item){
                    $item['price'] = '￥'.$item['min_price'].'~'.'￥'.$item['max_price'];
                    if($item['max_price'] == $item['min_price']){
                        $item['price'] = '￥'.$item['min_price'];
                    }
                }
                $coupon['goods_coupon'] = $goods_coupon;
            }
            if($coupon['use_time_start']){
                $coupon['use_time_start'] = date('Y-m-d H:i:s',$coupon['use_time_start']);
                $coupon['use_time_end'] = date('Y-m-d H:i:s',$coupon['use_time_end']);
            }
        }

        return $coupon;
    }

    /**
     * @notes 领券记录
     * @param $get
     * @return array
     * @author cjhao
     * @date 2022/1/13 18:07
     */
    public static function log($get){
        $where[] = ['CL.del','=',0];
        $where[] = ['CL.coupon_id','=',$get['id']];

        if(isset($get['keyword']) && $get['keyword']){
            switch($get['search_type']) {
                case 'sn';
                    $where[] = ['U.sn', '=', $get['keyword']];
                    break;
                case 'nickname';
                    $where[] = ['U.nickname', '=', $get['keyword']];
                    break;
                case 'mobile';
                    $where[] = ['U.mobile', '=', $get['keyword']];
                    break;
            }
        }

        if(isset($get['status']) && $get['status'] != '') {
            $where[] = ['CL.status', '=', $get['status']];
        }

        $log_count = CouponList::alias('CL')
            ->join('user U','CL.user_id = U.id')
            ->where($where)
            ->count();

        $log_list =  CouponList::alias('CL')
            ->join('user U','CL.user_id = U.id')
            ->where($where)
            ->field('CL.coupon_id,CL.status as cl_status,coupon_code,CL.create_time as cl_create_time,CL.use_time,U.nickname,U.avatar,U.mobile,U.sn')
            ->page($get['page'], $get['limit'])
            ->select();
        $coupon_list = Coupon::where(['del'=>0])->column('name','id');
        foreach ($log_list as &$item)
        {
            $item['coupon_name'] = $coupon_list[$item['coupon_id']] ?? '';
            $item['avatar'] = UrlServer::getFileUrl($item['avatar']);
            $item['status_desc'] = $item['cl_status'] ? '已使用' : '未使用';
            $item['cl_create_time'] = date('Y-m-d H:i:s',$item['cl_create_time']);
            $item['use_time_desc'] = $item['use_time'] ? date('Y-m-d H:i:s',$item['use_time']) : '';
        }
        return ['count'=>$log_count , 'lists'=>$log_list];
    }


    /**
     * @notes 优惠券统计
     * @return array
     * @author cjhao
     * @date 2022/1/19 9:17
     */
    public static function couponStatistics($get){
        $where[] = ['del','=',0];
        if(isset($get['name']) && $get['name']){
            $where[] = ['name','like','%'.$get['name'].'%'];
        }
        if(isset($get['get_type']) && $get['get_type']){
            $where[] = ['get_type','=',$get['get_type']];
        }
        if(isset($get['create_time']) && $get['create_time']){
            $where[] = ['create_time','>=',$get['create_time']];
        }
        if(isset($get['end_time']) && $get['end_time']){
            $where[] = ['create_time','<=',$get['end_time']];
        }
        $all = Coupon::where($where)->count();
        $not_start = Coupon::where(['status'=>CouponEnum::COUPON_STATUS_NOT])->where($where)->count();
        $ing = Coupon::where(['status'=>CouponEnum::COUPON_STATUS_CONDUCT])->where($where)->count();
        $end = Coupon::where(['status'=>CouponEnum::COUPON_STATUS_END])->where($where)->count();

        return [
            'coupon_all'        => $all,
            'coupon_not_start'  => $not_start,
            'coupon_ing'        => $ing,
            'coupon_end'        => $end,
        ];
    }
}