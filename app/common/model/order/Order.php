<?php

namespace app\common\model\order;

use app\common\basics\Models;
use app\common\enum\OrderEnum;
use app\common\enum\OrderGoodsEnum;
use app\common\enum\PayEnum;
use app\common\model\Client_;
use app\common\model\Pay;
use app\common\model\shop\Shop;
use app\common\model\user\User;
use app\common\server\ConfigServer;
use think\facade\Db;

/**
 * Class Order
 * @package app\common\model\order
 */
class Order extends Models
{
    //json转数组
    protected $json = ['address_snap'];
    protected $jsonAssoc = true;
    /**
     * @notes 关联OrderGoods模型
     * @return \think\model\relation\HasMany
     * @author suny
     * @date 2021/7/13 6:47 下午
     */
    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'id');
    }

    /**
     * @notes 一对多关联订单日志模型
     * @return \think\model\relation\HasMany
     * @author ljj
     * @date 2021/9/17 10:42 上午
     */
    public function orderLog()
    {
        return $this->hasMany(OrderLog::class, 'order_id', 'id');
    }
    /**
     * @notes 关联门店
     * @return \think\model\relation\HasOne
     * @author cjhao
     * @date 2021/9/16 16:16
     */
    public function shop()
    {

        return $this->hasOne(Shop::class, 'id', 'shop_id')
            ->append(['province_name','city_name','district_name'])
            ->field('id,name,phone,province_id,city_id,district_id,address');
    }

    public function getAppointTimeAttr($value,$data)
    {
        if(OrderEnum::ORDER_TYPE_YOUSELF_TAKE == $data['order_type'] && OrderEnum::APPOINT_NO  == $data['is_appoint']){
            return '立即取餐';
        }
        return date('m-d H:i:s',(int)$value);

    }

    /**
     * @notes 订单类型
     * @param $value
     * @param $data
     * @return string|string[]
     * @author suny
     * @date 2021/7/13 6:47 下午
     */
    public function getOrderTypeTextAttr($value, $data)
    {
        return OrderEnum::getOrderTypeDesc($data['order_type']);
    }

    /**
     * @notes 订单状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author suny
     * @date 2021/7/13 6:47 下午
     */
    public function getOrderStatusTextAttr($value, $data)
    {
        if( OrderEnum::ORDER_TYPE_YOUSELF_TAKE == $data['order_type']){
            return OrderEnum::getPickUpOrderStatus($data['order_status']);
        }
        return OrderEnum::getTakeOutOrderStatus($data['order_status']);
    }

    /**
     * @notes 订单支付方式
     * @param $value
     * @param $data
     * @return array|mixed|string|string[]
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function getPayWayTextAttr($value, $data)
    {

        return PayEnum::getPayWay($data['pay_way']);
    }

    /**
     * @notes 订单支付状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function getPayStatusTextAttr($value, $data)
    {
        return PayEnum::getPayStatus($data['pay_status']);
    }

    /**
     * @notes 就餐方式
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2021/9/14 4:04 下午
     */
    public function getDiningTypeTextAttr($value, $data)
    {
        return ($data['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) ? OrderEnum::getDiningTypeDesc($data['dining_type']) : '外卖配送';
    }

    /**
     * @notes 订单商品数量
     * @param $value
     * @param $data
     * @return int
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function getGoodsCountAttr($value, $data)
    {

        return count($this->order_goods);
    }

    /**
     * @notes 订单用户
     * @return \think\model\relation\HasOne
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function user()
    {

        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @notes 返回是否显示支付按钮
     * @param $value
     * @param $data
     * @return int
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function getPayBtnAttr($value, $data)
    {

        $btn = 0;
        if ($data['order_status'] == OrderEnum::ORDER_STATUS_NO_PAID && $data['pay_status'] == PayEnum::UNPAID) {
            $btn = 1;
        }
        return $btn;
    }

    /**
     * @notes 返回是否显示取消订单按钮
     * @param $value
     * @param $data
     * @return int
     * @author suny
     * @date 2021/7/13 6:48 下午
     */
    public function getCancelBtnAttr($value, $data)
    {
        $btn = 0;
        //如果是大于制作中，不允许取消
        if(OrderEnum::ORDER_STATUS_DELIVERY < $data['order_status']){
            return $btn;
        }
        //已支付订单
        if(PayEnum::ISPAID == $data['pay_status']){
            //多长时间内允许客户自动取消
            $cancel_limit = ConfigServer::get('transaction', 'paid_order_cancel_time', 5);
            $limit_time = strtotime($data['create_time']) + $cancel_limit * 60;
            if ( $limit_time > time() ) {
                $btn = 1;
            }
        }
        if(PayEnum::UNPAID == $data['pay_status']){

            //未支付订单
            if( $data['cancel_time'] > time() ){
                $btn = 1;
            }
        }

        return $btn;
    }

    /**
     * @notes 再来一单按钮
     * @param $value
     * @param $data
     * @return int
     * @author ljj
     * @date 2021/9/24 5:07 下午
     */
    public function getAgainBtnAttr($value, $data)
    {
        $btn = 0;
        if ($data['order_status'] > OrderEnum::ORDER_STATUS_GOODS) {
            $btn = 1;
        }
        return $btn;
    }

    /**
     * @notes 取消订单时间
     * @param $value
     * @param $data
     * @return false|float|int|string
     * @author suny
     * @date 2021/7/13 6:49 下午
     */
    public function getOrderCancelTimeAttr($value, $data)
    {

        $end_time = '';
        if (is_string($data['create_time'])) {
            $data['create_time'] = strtotime($data['create_time']);
        }
        if ($data['order_status'] == 0 && $data['pay_status'] == 0) {
            $order_cancel_time = ConfigServer::get('transaction', 'unpaid_order_cancel_time', 5);
            $end_time = $data['create_time'] + $order_cancel_time * 60;
        }
        return $end_time;
    }

    /**
     * @notes 支付时间
     * @param $value
     * @param $data
     * @return false|string
     * @author ljj
     * @date 2021/9/14 4:33 下午
     */
    public function getPayTimeAttr($value, $data)
    {
        return $value ? date('Y-m-d H:i:s',$value) : '未支付';
    }

    /**
     * @notes 收货地址
     * @param $value
     * @param $data
     * @return string
     * @author ljj
     * @date 2021/9/17 3:10 下午
     */
    public function getDeliveryAddressAttr($value, $data)
    {
        if (empty($data['address_snap'])) {
            return '未知';
        }
//        $region = Db::name('dev_region')
//            ->where('id', 'IN', [$data['address_snap']['province_id'], $data['address_snap']['city_id'], $data['address_snap']['district_id']])
//            ->order('level asc')
//            ->column('name');

//        $region_desc = implode('', $region);
        return $data['address_snap']['location'].$data['address_snap']['address'];
    }

    /**
     * @notes 订单退款状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2021/9/22 10:58 上午
     */
    public function getRefundStatusDescAttr($value,$data)
    {
        return OrderEnum::getRefundStatus($data['refund_status']);
    }

    /**
     * @notes 退款时间
     * @param $value
     * @return false|string
     * @author ljj
     * @date 2021/9/22 10:59 上午
     */
    public function getRefundTimeAttr($value)
    {
        return ($value != null) ? date('Y-m-d H:i:s',$value) : '-';
    }
}