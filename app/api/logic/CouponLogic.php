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
namespace app\api\logic;
use app\common\enum\CouponEnum;
use app\common\enum\CouponListEnum;
use app\common\enum\GoodsEnum;
use app\common\model\Cart;
use app\common\model\goods\Goods;
use app\common\model\marketing\Coupon;
use app\common\model\marketing\CouponGoods;
use app\common\model\marketing\CouponList;
use app\common\model\shop\Shop;
use think\facade\Db;

/**
 * 优惠券逻辑层
 * Class CouponLogic
 * @package app\admin\logic
 */
class CouponLogic {

    /**
     * @notes 优惠券列表
     * @param $shop_id
     * @param $user_id
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/19 10:39
     */
    public static function lists($shop_id,$user_id){
        \app\common\logic\CouponLogic::updateOverCoupon();
        $coupon_list = Coupon::where(['shop_id'=>$shop_id,'status'=>CouponEnum::COUPON_STATUS_CONDUCT,'del'=>0])
                    ->field('id,shop_id,name,money,condition_type,use_time_type,use_time_start,use_time_end,use_time,condition_money,use_goods_type')
                    ->select();
        $my_coupon = [];
        if($user_id){
            $my_coupon = CouponList::where(['user_id'=>$user_id])->column('coupon_id');
        }
        foreach ($coupon_list as $coupon){
            self::getCouponInfo($coupon,1);
            //是否已领取
            $coupon['is_get'] = false;
            if(in_array($coupon['id'],$my_coupon)){
                $coupon['is_get'] = true;
            }
        }
        return $coupon_list->hidden(['condition_type','shop_id','condition_money','use_time_type','use_time_start','use_time_end','use_time','use_goods_type'])->toArray();
    }


    /**
     * @notes 领取优惠券
     * @param $coupon_id
     * @param $user_id
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2022/1/19 11:25
     */
    public static function getCoupon($coupon_id,$user_id){
        if(empty($coupon_id)){
            return '请选择优惠券';
        }

        $coupon = Coupon::where(['id'=>$coupon_id,'del'=>0,'status'=>CouponEnum::COUPON_STATUS_CONDUCT])->find();
        if(empty($coupon)){
            return '优惠券已下架';
        }
        //验证总数量
        if(CouponEnum::SEND_TOTAL_TYPE_FIXED == $coupon['send_total_type']){
            $count = CouponList::where(['coupon_id' => $coupon['id']])->count();
            if($count >= $coupon['send_total']){
                return '该优惠券发放完了';
            }
        }

        //验证是否有次数领取
        if(CouponEnum::GET_NUM_TYPE_LIMIT == $coupon['get_num_type']){
            $count = CouponList::where(['user_id'=>$user_id,'coupon_id'=>$coupon['id']])->count();
            if($count >= $coupon['get_num']){
                return '该优惠券限领'.$coupon['get_num'].'张';
            }
        }else{
            $count = CouponList::where(['user_id'=>$user_id,'coupon_id'=>$coupon['id']])
                            ->whereDay('create_time','today')
                            ->count();
            if($count >= $coupon['get_num']){
                return '该优惠券每天限领'.$coupon['get_num'].'张';
            }
        }
        switch ($coupon['use_time_type']){
            case CouponEnum::USE_TIME_TYPE_FIXED:
                $over_time = $coupon['use_time_end'];
                break;
            case CouponEnum::USE_TIME_TYPE_TODAY:
                $over_time = time()+$coupon['use_time'] * 86400;
                break;
            case CouponEnum::USE_TIME_TYPE_TOMORROW:
                $over_time =  time()+($coupon['use_time']+1) * 86400;
                break;
        }
        $coupon_list = new CouponList();
        $coupon_list->user_id       = $user_id;
        $coupon_list->coupon_id     = $coupon_id;
        $coupon_list->coupon_code   = create_code();
        $coupon_list->status        = CouponListEnum::STATUS_NOT_USE;
        $coupon_list->over_time     = $over_time;
        $coupon_list->create_time   = time();
        $coupon_list->save();
        return true;

    }

    /**
     * @notes 我的优惠券
     * @param $shop_id
     * @param $type
     * @param $user_id
     * @return mixed
     * @author cjhao
     * @date 2022/1/19 15:23
     */
    public static function myCoupon($shop_id,$type,$user_id){
        $where[] = ['user_id','=',$user_id];
        $where[] = ['CL.del','=',0];
        if($shop_id){
            $where[] = ['shop_id','=',$shop_id];
        }
        switch ($type){
            //可使用的
            case 1:
                $where[] = ['CL.status','=',CouponListEnum::STATUS_NOT_USE];
                $where[] = ['CL.over_time','>',time()];
                break;
            case 2:
                $where[] = ['CL.status','=',CouponListEnum::STATUS_USE];
                break;
            case 3:
                $where[] = ['CL.status','=',CouponListEnum::STATUS_NOT_USE];
                $where[] = ['CL.over_time','<=',time()];
                break;
        }

        $coupon_list = CouponList::alias('CL')
                    ->join('coupon C','CL.coupon_id = C.id')
                    ->where($where)
                    ->field('C.id,C.shop_id,C.name,C.money,C.condition_type,C.use_time_type,C.use_time_start,C.use_time_end,C.use_time,C.condition_money,C.use_goods_type,CL.over_time')
                    ->select();
        $shop_ids = array_column($coupon_list->toArray(),'shop_id');
        $shop_list = Shop::where(['id'=>$shop_ids])->column('name','id');
        foreach ($coupon_list as $coupon){
            self::getCouponInfo($coupon,2);
            $coupon['shop_name'] = $shop_list[$coupon['shop_id']] ?? '全店通用';

        }
        $notuse_count = CouponList::where(['user_id'=>$user_id,'status'=>CouponListEnum::STATUS_NOT_USE])->where('over_time','>',time())->count();
        $use_count = CouponList::where(['user_id'=>$user_id,'status'=>CouponListEnum::STATUS_USE])->count();
        $overtime_count = CouponList::where(['user_id'=>$user_id,'status'=>CouponListEnum::STATUS_USE])->where('over_time','<=',time())->count();
        return
        $result = [
            'list'              => $coupon_list->hidden(['shop_id','condition_type','condition_money','use_time_type','use_time_start','use_time_end','use_time','use_goods_type'])->toArray(),
            'statistics'        => [
                'notuse_count'       => $notuse_count,
                'use_count'          => $use_count,
                'overtime_count'     => $overtime_count,
                ]
        ];

    }


    /**
     * @notes 获取优惠券基本信息
     * @param $coupon
     * @param $type:1-领取中心；2-我的优惠券
     * @author cjhao
     * @date 2022/1/19 14:43
     */
    public static function getCouponInfo($coupon,$type = 1){
        $coupon['use_tips'] = '满0元可用';
        if(CouponEnum::CONDITION_TYPE_FULL == $coupon['condition_type']){
            $coupon['use_tips'] = '满'.$coupon['condition_money'].'元使用';
        }

        //优惠券可用商品信息
        $coupon['use_goods_tips'] = '全店通用';
        $coupon['use_goods_list'] = '';
        if(CouponEnum::USE_GOODS_TYPE_NOT  != $coupon['use_goods_type']){
            $goods_name = Goods::alias('G')
                        ->join('coupon_goods CG','CG.goods_id = G.id')
                        ->join('shop_goods SG','G.id = SG.goods_id')
                        ->where(['G.status' => GoodsEnum::STATUS_SHELVES,'shop_id'=>$coupon['shop_id']])
                        ->column('name');

            $goods_name = implode('、',$goods_name);
            if(CouponEnum::USE_GOODS_TYPE_ALLOW == $coupon['use_goods_type']){
                $coupon['use_goods_tips'] = '部分商品可用';
                $coupon['use_goods_list'] = '仅限 '.$goods_name.' 商品可用';
            }else{
                $coupon['use_goods_tips'] = '部分商品不可用';
                $coupon['use_goods_list'] = $goods_name.' 商品不可用';

            }


        }
        if(1 == $type){

            switch ($coupon['use_time_type']){
                case CouponEnum::USE_TIME_TYPE_FIXED:
                    $coupon['over_time'] = '有效期至:'.date('Y-m-d H:i:s',$coupon['use_time_end']);
                    break;
                case CouponEnum::USE_TIME_TYPE_TODAY:
                    $over_time =  $over_time = time()+$coupon['use_time'] * 86400;
                    $coupon['over_time'] = '有效期至:'.date('Y-m-d H:i:s',$over_time);
                    break;
                case CouponEnum::USE_TIME_TYPE_TOMORROW:
                    $over_time =  time()+($coupon['use_time']+1) * 86400;
                    $coupon['over_time'] = '有效期至:'.date('Y-m-d H:i:s',$over_time);
                    break;
            }

        }else{
            $coupon['over_time'] = '有效期至:'.date('Y-m-d H:i:s',$coupon['over_time']);

        }
        return $coupon;
    }

    /**
     * @notes 下单获取优惠券
     * @param $shop_id
     * @param $user_id
     * @return array|string
     * @author cjhao
     * @date 2022/1/20 10:26
     */
    public static function getOrderCoupon($shop_id,$user_id){
        try{

            $coupon_list = CouponList::alias('CL')
                ->join('coupon C','CL.coupon_id = C.id')
                ->where(['shop_id'=>$shop_id,'user_id'=>$user_id,'CL.del'=>0,'CL.status'=>CouponListEnum::STATUS_NOT_USE])
                ->where('over_time','>',time())
                ->field('C.id,CL.id as cl_id,C.shop_id,C.name,C.money,C.condition_type,C.use_time_type,C.use_time_start,C.use_time_end,C.use_time,C.condition_money,C.use_goods_type,CL.over_time')
                ->select();

            if(empty($coupon_list)){
                return [
                    'usable'        => [],//可用
                    'unusable'      => [],//不可用
                ];
            }
            $cart_list = Cart::where(['shop_id'=>$shop_id,'user_id'=>$user_id])->select()->toArray();
            $data = [
                'usable'        => [],//可用
                'unusable'      => [],//不可用
            ];
            //处理优惠券信息
            foreach ($coupon_list as $coupon){
                //验证是否可用
                $check_coupon = self::checkCouponUsable($coupon,$cart_list);
                //获取优惠券信息
                self::getCouponInfo($coupon);
                //屏蔽信息
                $coupon->hidden(['shop_id','condition_type','condition_money','use_time_type','use_time_start','use_time_end','use_time','use_goods_type']);

                $coupon['tips'] = $check_coupon['tips'];
                //可用
                if(true == $check_coupon['is_usable']){
                    $data['usable'][] = $coupon;
                }else{
                    $data['unusable'][] = $coupon;
                }
            }
            return $data;

        }catch (\Exception $e){
            return $e->getMessage();
        }

    }

    /**
     * @notes 验证优惠券是否可用
     * @param $coupon
     * @param $cart_list
     * @author cjhao
     * @date 2022/1/19 18:10
     * 先验证优惠券商品类型，如果包含不可使用商品，直接返回false，
     * 然后在验证是否满足金额使用。
     */
    public static function checkCouponUsable($coupon,$cart_list){
        //获取购物车商品总额
        $cart_goods_list = CartLogic::getCartGoodsPrice($cart_list);

        $goods_ids = array_keys($cart_goods_list);
        $cart_goods_sum = array_sum($cart_goods_list);
        $goods_intersect = [];
        $goods_intersect_sum = 0;

        //指定商品验证
        if(CouponEnum::USE_GOODS_TYPE_NOT != $coupon['use_goods_type'] ){

            $coupon_goods_ids = CouponGoods::where(['coupon_id'=>$coupon['id']])->column('goods_id');
            $goods_intersect = array_intersect($goods_ids,$coupon_goods_ids);

            //指定商品可用，购物车里面没有可用商品，返回false
            if(CouponEnum::USE_GOODS_TYPE_ALLOW == $coupon['use_goods_type'] && !$goods_intersect){
                return [
                    'is_usable' => false,
                    'tips'      => '所结算商品不可使用该优惠券',
                ];
            }
            //指定商品不可用，购物车包含该商品，返回false
            if(CouponEnum::USE_GOODS_TYPE_BAN == $coupon['use_goods_type'] && $goods_intersect){
                return [
                    'is_usable' => false,
                    'tips'      => '所结算商品不可使用该优惠券',
                ];
            }

        }

        //使用金额门槛
        if(CouponEnum::CONDITION_TYPE_FULL == $coupon['condition_type']){
            //全部商品，未满足金额
            if(CouponEnum::USE_GOODS_TYPE_NOT == $coupon['use_goods_type'] && $cart_goods_sum < $coupon['condition_money']){
                return [
                    'is_usable' => false,
                    'tips'      => '所结算商品未满足使用金额',
                ];
            }
            //购物车中指定商品的总金额
            foreach ($goods_intersect as $goods_id){
                $goods_intersect_sum += $cart_goods_list[$goods_id] ?? 0;
            }
            //指定商品，未满足金额
            if(CouponEnum::USE_GOODS_TYPE_ALLOW == $coupon['use_goods_type'] && $goods_intersect_sum < $coupon['condition_money']){
                return [
                    'is_usable' => false,
                    'tips'      => '所结算商品未满足使用金额',
                ];
            }
            //购物车减去不可使用金额，剩余可用金额
            $diff_cart_sum = $cart_goods_sum - $goods_intersect_sum;
            if(CouponEnum::USE_GOODS_TYPE_BAN == $coupon['use_goods_type'] && $diff_cart_sum < $coupon['condition_money']){
                return [
                    'is_usable' => false,
                    'tips'      => '所结算商品未满足使用金额',
                ];
            }
        }

        return [
            'is_usable' => true,
            'tips'      => '',
        ];
    }



}