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
namespace app\shop\logic\marketing;
use app\common\basics\Logic;
use app\common\enum\CouponEnum;
use app\common\enum\GoodsEnum;
use app\common\enum\ShopGoodsEnum;
use app\common\model\marketing\CouponGoods;
use app\common\model\goods\Goods;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponList;
use app\common\server\UrlServer;
use think\Exception;
use think\facade\Db;

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
        $where[] = ['del','=',0];
        $where[] = ['shop_id','=',$get['shop_id']];
        if($get['type']){
            $where[] = ['status','=',$get['type']];
        }
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

        $count = Coupon::where($where)
                ->count();

        $lists = Coupon::where($where)
                ->page($page_no,$page_size)
                ->order('id desc')
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
     * @notes 添加优惠券
     * @param $post
     * @return bool
     * @author cjhao
     * @date 2022/1/13 14:48
     */
    public static function add($post){

        Db::startTrans();
        try {

            //优惠券信息
            $coupon = new Coupon();
            $coupon->shop_id            = $post['shop_id'];
            $coupon->name               = $post['name'];
            $coupon->money              = $post['money'];
            $coupon->send_total_type    = $post['send_total_type'];
            $coupon->send_total         = $post['send_total_type'] == CouponEnum::SEND_TOTAL_TYPE_FIXED ? $post['send_total'] : 0;
            $coupon->condition_type     = $post['condition_type'];
            $coupon->condition_money    = $post['condition_type'] == CouponEnum::CONDITION_TYPE_FULL ? $post['condition_money'] : 0;
            $coupon->use_time_type      = $post['use_time_type'];
            $coupon->use_time_start     = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED ? strtotime($post['use_time_start']) : 0;
            $coupon->use_time_end       = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED ? strtotime($post['use_time_end']) : 0;
            $coupon->use_time           = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_TODAY ? $post['use_time'] : $post['tomorrow_use_time'];
            $coupon->get_type           = $post['get_type'];
            $coupon->get_num_type       = $post['get_num_type'];
            $coupon->get_num            = $post['get_num_type'] == CouponEnum::GET_NUM_TYPE_LIMIT ? $post['get_num'] : $post['day_get_num'];
            $coupon->use_goods_type     = $post['use_goods_type'];
            $coupon->status             = CouponEnum::COUPON_STATUS_NOT;
            $coupon->save();

            if(CouponEnum::USE_GOODS_TYPE_NOT != $coupon->use_goods_type){
                $coupon_goods = [];
                foreach ($post['goods_ids'] as $goods_id){
                    $coupon_goods[] = [
                        'goods_id'  => $goods_id,
                    ];
                }
                $coupon->couponGoods()->saveAll($coupon_goods);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 编辑优惠券
     * @param $post
     * @return bool
     * @author cjhao
     * @date 2022/1/13 17:08
     */
    public static function edit($post){

        Db::startTrans();
        try {


            //优惠券信息
            $coupon = Coupon::find($post['id']);

            //优惠券已结束，不允许编辑
            if (CouponEnum::COUPON_STATUS_END == $coupon['status']) {
                throw new Exception('优惠券已结束,禁止编辑');
            }

            //优惠券进行中，只允许编辑发放数量
            if(CouponEnum::COUPON_STATUS_CONDUCT == $coupon['status']){

                if ($coupon['send_total_type'] == CouponEnum::SEND_TOTAL_TYPE_FIXED && $coupon['send_total'] > $post['send_total']) {
                    throw new Exception('调整后的发放数量不可少于原来的数量');
                }

                $coupon->name               = $post['name'];
                $coupon->send_total_type    = $post['send_total_type'];
                $coupon->send_total         = $post['send_total_type'] == 2 ? $post['send_total'] : 0;
                $coupon->save();

            }else{
                //优惠券未开始，可更新全部信息

                $coupon->name               = $post['name'];
                $coupon->money              = $post['money'];
                $coupon->send_total_type    = $post['send_total_type'];
                $coupon->send_total         = $post['send_total_type'] == CouponEnum::SEND_TOTAL_TYPE_FIXED ? $post['send_total'] : 0;
                $coupon->condition_type     = $post['condition_type'];
                $coupon->condition_money    = $post['condition_type'] == CouponEnum::CONDITION_TYPE_FULL ? $post['condition_money'] : 0;
                $coupon->use_time_type      = $post['use_time_type'];
                $coupon->use_time_start     = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED ? strtotime($post['use_time_start']) : 0;
                $coupon->use_time_end       = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_FIXED ? strtotime($post['use_time_end']) : 0;
                $coupon->use_time           = $post['use_time_type'] == CouponEnum::USE_TIME_TYPE_TODAY ? $post['use_time'] : $post['tomorrow_use_time'];
                $coupon->get_type           = $post['get_type'];
                $coupon->get_num_type       = $post['get_num_type'];
                $coupon->get_num            = $post['get_num_type'] == CouponEnum::GET_NUM_TYPE_LIMIT ? $post['get_num'] : $post['day_get_num'];
                $coupon->use_goods_type     = $post['use_goods_type'];
                $coupon->save();

                CouponGoods::where(['coupon_id'=>$coupon['id']])->delete();

                if(CouponEnum::USE_GOODS_TYPE_NOT != $coupon->use_goods_type){
                    $coupon_goods = [];
                    foreach ($post['goods_ids'] as $goods_id){
                        $coupon_goods[] = [
                            'goods_id'  => $goods_id,
                        ];
                    }
                    $coupon->couponGoods()->saveAll($coupon_goods);
                }
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
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
     * @notes 修改优惠券状态
     * @param $post
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/18 19:03
     */
    public static function changeStatus($post){
        $coupon = Coupon::find($post['id']);
        $coupon->status = $post['status'];
        $coupon->update_time = time();
        $coupon->save();

    }

    /**
     * @notes 删除优惠券
     * @param $id
     * @return bool
     * @author cjhao
     * @date 2022/1/13 18:21
     */
    public static function del($id){

        $time = time();
        $couponList = CouponList::where(['coupon_id'=>$id])
            ->findOrEmpty()->toArray();

        if ($couponList) {
            return '优惠券已被用户领取,不可删除';
        }
        // 优惠券主表
        Coupon::update([
            'id' => $id,
            'del' => 1,
            'update_time' => $time
        ]);
        return true;


    }


    /**
     * @notes 优惠券统计
     * @return array
     * @author cjhao
     * @date 2022/1/19 9:17
     */
    public static function couponStatistics($get){
        $where[] = ['del','=',0];
        $where[] = ['shop_id','=',$get['shop_id']];
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