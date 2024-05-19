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
namespace app\api\server;
use app\api\logic\CouponLogic;
use app\common\{enum\CouponListEnum,
    enum\ShopEnum,
    logic\OrderLogLogic,
    model\Cart,
    enum\OrderEnum,
    enum\GoodsEnum,
    model\marketing\CouponList,
    model\user\User,
    model\shop\Shop,
    logic\GoodsLogic,
    model\order\Order,
    enum\OrderLogEnum,
    model\shop\ShopGoods,
    enum\DefaultConfigEnum,
    model\order\OrderGoods,
    model\user\UserAddress,
    model\shop\ShopGoodsItem,
    server\ConfigServer,
    server\UrlServer};
use think\{Exception, facade\Db, model\Relation};
use app\admin\logic\integral\RuleLogic;
use app\common\logic\IntegralLogic;

/**
 * 下单功能类
 * Class OrderServer
 * @package app\api\server
 */
class OrderServer
{
    protected $params           = [];
    protected $user             = [];
    protected $shop             = [];  //门店信息
    protected $cart_list        = [];  //购物车商品
    protected $order_goods      = [];  //订单商品
    protected $address          = [];  //收货地址
    protected $total_num        = 0;   //商品数量
    protected $goods_amount     = 0;   //订单商品金额
    protected $total_amount     = 0;   //订单总金额
    protected $delivery_amount  = 0;   //配送费用
    protected $discount_amount  = 0;   //优惠金额
    protected $discount_amount_jifen  = 0;   //积分优惠金额
    protected $discount_jifen   = 0;   //使用多少积分
    protected $order            = '';  //订单编号
    protected $action           = '';  //动作
    protected $coupon_list_id   = '';  //用户优惠券id
    protected $coupon_id        = '';  //优惠券id
    protected $integral_type        = '';  //1不使用抵扣 2使用抵扣
    protected $zhuohao        = '';  //桌号



    public function __construct(array $post)
    {
        $this->params = $post;
        $this->action = $this->params['action'] ?? '';
        $this->setOrderBaseInfo();
    }

    /**
     * @notes 设置订单基本信息
     * @return $this
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/18 18:26
     */
    public function setOrderBaseInfo()
    {
        $this->user = User::find($this->params['user_id']);

        //获取门店信息
        $field = 'id,name,province_id,city_id,district_id,address,longitude,latitude,business_start_time,business_end_time,weekdays,delivery_distance,delivery_buy_limit,delivery_freight,status,delivery_type';

        if(OrderEnum::ORDER_TYPE_TAKE_AWAY == $this->params['order_type']){

            if(isset($this->params['address_id']) && $this->params['address_id']){

                $addr_where = ['id'=>$this->params['address_id']];
            }else{
                $addr_where = ['is_default'=>1];
            }
            $user_address = UserAddress::
                where(['user_id'=>$this->params['user_id'],'del'=>0])
                ->where($addr_where)
                ->append(['province_name','city_name','district_name'])
                ->findOrEmpty()->toArray();
            if($user_address){
                $field .=',round(st_distance_sphere(point('.$user_address['longitude'].','.$user_address['latitude'].'),point(longitude, latitude))/1000,2) as distance';
            }
            $this->address = $user_address;
        }

        $this->shop = Shop::field($field)->append(['province_name','city_name','district_name'])->findOrEmpty($this->params['shop_id'])->toArray();
        #营业到次日
        if ($this->shop['business_start_time']>$this->shop['business_end_time'] && !isset($this->params['action'])) {
            $this->shop['business_end_time']=(explode(':',$this->shop['business_end_time'])[0]+24).':'.explode(':',$this->shop['business_end_time'])[1];
        }
        // var_dump($this->shop);exit;
        if (OrderEnum::ORDER_TYPE_TAKE_AWAY == $this->params['order_type']) {
            $this->delivery_amount = $this->shop['delivery_freight'];
        }


        $this->cart_list = Cart::where(['shop_id'=>$this->params['shop_id'],'user_id'=>$this->params['user_id']])
            ->select()->toArray();

        $this->coupon_list_id = $this->params['cl_id'] ?? '';
        $this->integral_type = $this->params['jifen_dikou'];
        $this->zhuohao = isset($this->params['zhuohao'])?$this->params['zhuohao']:0;
        return $this;
    }


    /**
     * @notes 检查订单基础信息
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/8 15:58
     */
    public function checkOrderBaseData()
    {
        if(empty($this->shop)){
            throw new Exception('门店不存在');
        }

        if(DefaultConfigEnum::NO == $this->shop['status']) {
            throw new Exception('门店休息中');
        }
        $week = date('N');
        $shop_weekdays = explode(',',$this->shop['weekdays']);

        if(!in_array($week,$shop_weekdays)){
            throw new Exception('门店休息中');
        }

        if(empty($this->cart_list)){
            throw new Exception('请选择商品');
        }

        $delivery_type = explode(',',$this->shop['delivery_type']);
        if(!in_array($this->params['order_type'],$delivery_type)){
            throw new Exception('门店暂时不支持'.ShopEnum::getDeliveryTypeDesc($this->params['order_type']));
        }
        //门店自提验证预约时间
        if(OrderEnum::ORDER_TYPE_YOUSELF_TAKE == $this->params['order_type']){
            $appoint_time =  $this->params['appoint_time'] ?? '';
            if('' === $appoint_time){
                throw new Exception('请选择预约时间');
            }
            $business_start_time = strtotime(date('Y-m-d '.$this->shop['business_start_time']));
            $business_end_time   = strtotime(date('Y-m-d '.$this->shop['business_end_time']));

            #营业到次日
            if ($this->shop['business_start_time']>$this->shop['business_end_time']) {
                $business_end_time   = strtotime(date('Y-m-d '.$this->shop['business_end_time']))+86400;
            }

            if($appoint_time > 1 && ($business_start_time > $appoint_time || $business_end_time < $appoint_time) ){
                throw new Exception('您预约的时间门店已打烊了');
            }

            $mobile = $this->params['mobile'] ?? '';
            if('submit' === $this->action && empty($mobile)){
                $this->params['mobile']='';
                // throw new Exception('请输入手机号码');
            }

        }else{
            if('submit' === $this->action && empty($this->address)){
                throw new Exception('收货地址不存在，请重新选择');
            }
            if('submit' === $this->action && $this->shop['distance'] > $this->shop['delivery_distance']){
                throw new Exception('收货地址超出配送范围');
            }
        }
        //验证优惠券
        $this->checkOrderCoupon();
        // var_dump($this->params);
        //验证积分抵扣
        $this->checkOrderIntegral();

        return $this;
    }

    /**
     * @notes 验证优惠券是否可用
     * @return $this
     * @throws Exception
     * @author cjhao
     * @date 2022/1/20 11:14
     */
    private function checkOrderCoupon(){
        if(empty($this->coupon_list_id)){
            return true;
        }
        //验证优惠券是否可用
        $coupon = CouponList::alias('CL')
            ->join('coupon C','CL.coupon_id = C.id')
            ->where(['shop_id'=>$this->shop['id'],'user_id'=>$this->user['id'],'CL.id'=>$this->coupon_list_id])
            ->field('C.id,CL.id as clid,C.shop_id,C.name,C.money,C.condition_type,C.use_time_type,C.use_time_start,C.use_time_end,C.use_time,C.condition_money,C.use_goods_type,CL.over_time,CL.status')
            ->find();

        if(empty($coupon)){
            throw new Exception("该优惠券不存在");
        }
        if(CouponListEnum::STATUS_NOT_USE != $coupon['status'] || $coupon['over_time'] < time()){
            throw new Exception("该优惠券已失效");
        }
        $check_coupon = CouponLogic::checkCouponUsable($coupon,$this->cart_list);
        if(false == $check_coupon['is_usable']){
            throw new Exception($check_coupon['tips']);
        }
        $this->discount_amount = $coupon['money'];
        $this->coupon_id = $coupon['id'];
    }

    /**
     * @notes 验证积分抵扣
     * @return $this
     * @throws Exception
     * @author yong
     * @date 2023/2/18 15:14
     */
    private function checkOrderIntegral(){
        // var_dump($this->integral_type);
        // exit;
        // if($this->integral_type!='2'){
            // return true;
        // }

        // $this->discount_amount_jifen = round($this->user['integral']/100,2);
    }

    /**
     * @notes 计算订单
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/9 10:36
     */
    public function calculateOrder()
    {
        $goods_ids = array_column($this->cart_list,'goods_id');
        $goods_list = GoodsLogic::getGoods($this->shop['id'],$goods_ids);
        $shop_goods = $goods_list['shop_goods'];
        $price_list = $goods_list['price_list'];

        //订单商品总价格
        $total_amount = 0;
        //订单商品总数量
        $total_num = 0;
        //订单商品信息
        $order_goods = [];

        foreach ($this->cart_list as $cart){

            $total_goods_price    = 0;                      //商品总金额
            $total_material_price = 0;                      //物料总金额
            $goods_num = $cart['num'];                      //商品数量
            $material_ids = $cart['material_ids'];          //购物车的物料ids
            $goods = $shop_goods[$cart['goods_id']] ?? [];  //购物车商品
            $goods_materail = [];                           //商品物料信息

            if('submit' === $this->action && empty($goods)){
                throw new Exception('商品信息错误');
            }
            if('submit' === $this->action && GoodsEnum::STATUS_SOLD_OUT == $goods['shop_status']){
                throw new Exception($goods['name'].'已下架');
            }
            $goods_item = $price_list['goods_item'][$cart['goods_id']][$cart['item_id']] ?? [];
            if('submit' === $this->action && empty($goods_item)){
                throw new Exception('商品规格信息错误');
            }

            $item_stock  = $goods_item['stock'] ?? 0;
            $goods_price = $goods_item['price'];
            //验证库存是否充足
            if('submit' === $this->action && $item_stock < $goods_num){
                throw new Exception($goods['name'] . '(' . $goods_item['spec_value_str'] . ')，库存不足' );
            }
            $total_goods_price += $goods_price * $goods_num;
            //验证物料
            if($material_ids){
                $materail_category_list = $price_list['goods_material'][$cart['goods_id']] ?? [];

                foreach ($materail_category_list as $materail_category) {
                    $materail_list = array_column($materail_category['material'],null,'id');
                    $materail_list_ids = array_keys($materail_list);
                    $materail_intersect = array_intersect($material_ids,$materail_list_ids);

                    //验证物料是否支持多选
                    if('submit' === $this->action && count($materail_intersect) >= 2 && 0 == $materail_category['all_choice']){

                        $material_name = array_map(function ($materail_intersect_id) use ($materail_category){
                            return $materail_category['material'][$materail_intersect_id]['name'] ?? '';
                        },$materail_intersect);

                        throw new Exception($goods['name'] . '(' . implode('、',$material_name) .')，不支持多选');
                    }

                    //验证物料是否可以下单、todo 后续验证库存
                    foreach ($material_ids as $key => $material_id){
                        if(in_array($material_id,$materail_list_ids)){
                            unset($material_ids[$key]);
                            $goods_materail[] =$materail_list[$material_id];
                            $material_price = $materail_list[$material_id]['price'];
                            $total_material_price += $material_price * $goods_num;
                        }

                    }

                }

                if('submit' === $this->action && $material_ids){
                    throw new Exception($goods['name'] .'商品信息已修改，请重新选择商品');
                }
            }

            $order_goods[] = [
                'id'            => $goods['id'],
                'name'          => $goods['name'],
                'image'         => $goods['image'],
                'status'        => $goods['status'],
                'shop_status'   => $goods['shop_status'],
                'remark'        => $goods['remark'],
                'spec_type'     => $goods['spec_type'],
                'item_id'       => $goods_item['id'],
                'spec_value_str'=> $goods_item['spec_value_str'],
                'goods_price'   => $goods_item['price'],
                'goods_num'     => $goods_num,
                'total_price'   => $total_goods_price+$total_material_price,
                'material_name' => array_column($goods_materail,'name'),
                'material_list' => $goods_materail
            ];
            $total_num += $goods_num;
            $total_amount += $total_goods_price+$total_material_price;
        }

        //如果是外卖，验证是否满足外卖起送价
        if(OrderEnum::ORDER_TYPE_TAKE_AWAY == $this->params['order_type'] && $total_amount < $this->shop['delivery_buy_limit']){
            throw new Exception('外卖需'.$this->shop['delivery_buy_limit'].'元起送');
        }
        $this->order_goods  = $order_goods;
        //总数量
        $this->total_num    = $total_num;
        //商品总价
        $this->goods_amount = $total_amount;
        //总价格（包含运费）
        $this->total_amount = round($this->goods_amount + $this->delivery_amount,2);
        return $this;
    }


    /**
     * @notes 获取门店信息
     * @return array
     * @author cjhao
     * @date 2021/9/9 10:54
     */
    public function getShopInfo()
    {
        return $this->shop;
    }

    /**
     * @notes 获取商品总价
     * @return float
     * @author ljj
     * @date 2021/10/20 9:53 上午
     */
    public function getGoodsAmount()
    {
//        $goods_amount = round($this->goods_amount - $this->discount_amount,2);
//        if($goods_amount < 0){
//            $goods_amount = 0;
//        }
        return $this->goods_amount;
    }

    /**
     * @notes 获取订单总价（包含运费）
     * @return float
     * @author cjhao
     * @date 2021/10/19 17:29
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * @notes 获取应付金额
     * @return int
     * @author cjhao
     * @date 2022/1/20 10:54
     */
    public function getOrderAmount(){
        $order_amount = round($this->total_amount - $this->discount_amount - $this->discount_amount_jifen,2);
        if($order_amount < 0){
            $order_amount = 0;
        }
        return $order_amount;
    }


    /**
     * @notes 获取优惠金额
     * @return int
     * @author cjhao
     * @date 2022/1/20 11:23
     */
    public function getDiscountAmount(){
        if($this->total_amount < $this->discount_amount){
            return $this->total_amount;
        }
        return $this->discount_amount;
    }

    /**
     * @notes 获取积分优惠金额
     * @return int
     * @author cjhao
     * @date 2022/1/20 11:23
     */
    public function getDiscountAmountIntegral(){
        #应该使用多少积分（一般积分多过应付金额才有必要） 计算 通过 订单总价-优惠金额=原来的应付金额，

        
        if($this->integral_type!='2'){
            return 0;
        }

        if (!$this->isUseIntegral()) {
            return 0;
        }

        $yingfu=$this->getTotalAmount()-$this->getDiscountAmount();
        
        #积分配置
        $config=RuleLogic::getConfig();
        #每多少积分几口一元
        $one_yuan=isset($config['one_yuan'])?$config['one_yuan']:100;

        #最大使用积分额度
        $use_integral=round($yingfu*$one_yuan,2);

        #用户积分不足直接返回
        if ($this->user['integral']<$use_integral) {
            // echo $this->user['integral']."buzu".$use_integral;
            $this->discount_amount_jifen=round($this->user['integral']/$one_yuan,2);
            $this->discount_jifen=round($this->user['integral'],2);
            return $this->discount_amount_jifen;
        }

        #积分足够多直接抵扣所有应付金额
        $this->discount_amount_jifen=($yingfu);
        $this->discount_jifen=round($yingfu*$one_yuan,2);

        return $this->discount_amount_jifen;
    }

    /**
     * 判断是否达到使用积分条件
     */
    public function isUseIntegral(){
        $all_money=$this->getTotalAmount();

        $config=RuleLogic::getConfig();
        // var_dump($config);
        // exit;
        if ($all_money<$config['min_order_money'] || $this->user['integral']<$config['min_integral']) {
            return 0;
        }
        return 1;
    }

    /**
     * @notes 获取订单数量
     * @return array
     * @author cjhao
     * @date 2021/9/9 10:59
     */
    public function getOrderNum()
    {
        return $this->total_num;
    }

    /**
     * @notes 获取用户地址
     * @return array
     * @author cjhao
     * @date 2021/9/9 11:20
     */
    public function getUserAddress()
    {
        return $this->address;
    }

    /**
     * @notes 获取配送费
     * @return mixed
     * @author cjhao
     * @date 2021/9/9 11:25
     */
    public function getDeliveryAmount()
    {
        //外卖订单
        if(OrderEnum::ORDER_TYPE_TAKE_AWAY == $this->params['order_type']){
            return $this->shop['delivery_freight'];
        }
        return 0;
    }

    /**
     * @notes 获取订单商品
     * @return array
     * @author cjhao
     * @date 2021/9/9 11:42
     */
    public function getOrderGoods()
    {
        return $this->order_goods;
    }

    /**
     * @notes 获取支付方式
     * @return mixed
     * @author cjhao
     * @date 2021/9/9 11:54
     */
    public function getPayWay()
    {
        return $this->params['pay_way'];
    }

    /**
     * @notes 获取预约信息
     * @return array
     * @author cjhao
     * @date 2021/9/9 11:59
     */
    public function getAppointInfo()
    {
        //外卖订单，且预约了时间
        if(OrderEnum::ORDER_TYPE_YOUSELF_TAKE == $this->params['order_type'] && $this->params['appoint_time'] > 1){
            return ['is_appoint'    => OrderEnum::APPOINT_YES, 'appoint_time'  => $this->params['appoint_time']];
        }
        return ['is_appoint' => OrderEnum::APPOINT_NO,'appoint_time' => time() ];
    }

    /**
     * @notes 下单接口
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/9 11:59
     */
    public function sumbitOrder()
    {
        $now = time();
        $appoint_info = $this->getAppointInfo();

        $order = new Order();
        $take_code = '';
        if(OrderEnum::ORDER_TYPE_YOUSELF_TAKE == $this->params['order_type']){
            $take_code = generateTakeFoodCode($this->shop['id']);
        }

        $cancel_time = ConfigServer::get('transaction', 'unpaid_order_cancel_time', 5) * 60;
        $order_cancel_time = '';
        if($cancel_time > 0){
            $order_cancel_time = $now+$cancel_time;
        }
        $order->shop_id         = $this->shop['id'];
        $order->user_id         = $this->user['id'];
        $order->order_sn        = createSn('order', 'order_sn');
        $order->pay_way         = 0;
        $order->take_code       = $take_code;
        $order->pay_status      = OrderEnum::PAY_STATUS_NO;
        $order->dining_type     = $this->params['dining_type'];
        $order->order_status    = OrderEnum::ORDER_STATUS_NO_PAID;
        $order->order_type      = $this->params['order_type'];
        $order->is_appoint      = $appoint_info['is_appoint'];
        $order->appoint_time    = $appoint_info['appoint_time'];
        $order->delivery_amount = $this->delivery_amount;                   //配送费用
        $order->discount_amount = $this->getDiscountAmount();               //优惠金额
        $order->integral_amount = $this->getDiscountAmountIntegral();       //积分优惠金额
        $order->use_integral    = $this->discount_jifen;              //使用积分额度
        $order->order_amount    = $this->getOrderAmount();                  //应付金额
        $order->total_amount    = $this->total_amount;                      //商品总价
        $order->total_num       = $this->total_num;                         //商品总数量
        $order->coupon_id       = $this->coupon_id;                         //优惠券id
        $order->zhuohao         = $this->zhuohao;                           //桌号
        $order->address_snap    = json_encode($this->address, JSON_UNESCAPED_UNICODE);
        $order->user_remark     = $this->params['user_remark'] ?? '';
        $order->cancel_time     = $order_cancel_time;        //todo 下单时候记录取消订单时间，支付完成后把该字段清空
        $order->create_time     = $now;

        //外卖订单收货地址信息
        if(OrderEnum::ORDER_TYPE_TAKE_AWAY == $this->params['order_type']){
            $order->consignee   = $this->address['contact'];
            $order->mobile      = $this->address['telephone'];

        }else{
            //到店订单记录手机号码
            $order->mobile      = $this->params['mobile'];
        }
        $order->save();

        //订单商品信息
        $order_goods = [];
        foreach ($this->order_goods as $goods){
            $material_ids = array_column($goods['material_list'],'id');
            $order_goods[] = [
                'shop_id'       => $order->shop_id,
                'order_id'      => $order->id,
                'goods_id'      => $goods['id'],
                'goods_name'    => $goods['name'],
                'item_id'       => $goods['item_id'],
                'image'         => UrlServer::setFileUrl($goods['image']),
                'spec_value_str'=> $goods['spec_value_str'],
                'material_ids'  => implode(',',$material_ids),
                'goods_num'     => $goods['goods_num'],
                'goods_price'   => $goods['goods_price'],
                'goods_snap'    => json_encode($goods, JSON_UNESCAPED_UNICODE),
            ];
        }
        (new OrderGoods())->saveAll($order_goods);

        $this->order = $order;
        //记录日志
        OrderLogLogic::record(OrderLogEnum::TYPE_USER,
            OrderLogEnum::USER_ADD_ORDER,
                    $this->order->id,
                    $this->user['id'],
                    $this->shop['id']
                    );

        //标记优惠券已使用
        if($this->coupon_id){
            CouponList::where(['id'=>$this->coupon_list_id,'user_id'=>$this->user['id']])
                ->update(['use_time'=>$now,'order_id'=>$order->id,'status'=>CouponListEnum::STATUS_USE]);

        }

        if ($order->use_integral>0) {
            $model_user=new User();
            $model_user->where('id',$this->user['id'])->dec('integral',$order->use_integral)->update();
            $integral_log=array(
                'order_id'=>$order->id,
                'user_id'=>$this->user['id'],
                'num'=>($order->use_integral)*-1,
                'create_time'=>time(),
                'update_time'=>time(),
                'remark'=>'下单积分抵扣',
            );
            (new IntegralLogic())->addIntegralLog($integral_log);
        }

        return $this;
    }


    /**
     * @notes 删除购物车
     * @return $this
     * @author cjhao
     * @date 2021/9/9 16:56
     */
    public function delCart()
    {
        Cart::where(['user_id'=>$this->user['id'],'shop_id'=>$this->shop['id']])->delete();
        return $this;
    }


    /**
     * @notes 更新商品库存
     * @return $this
     * @author cjhao
     * @date 2021/9/9 16:57
     */
    public function updateStock()
    {
        //扣减库存
        foreach ($this->order_goods as $goods){
            ShopGoodsItem::where(['shop_id'=>$this->shop['id'],'item_id'=>$goods['item_id']])->update(['stock'=>Db::raw('stock-'.$goods['goods_num'])]);
        }
        $goods_ids = array_column($this->order_goods,'id');
        $goods_total_stock = ShopGoodsItem::where(['shop_id'=>$this->shop['id'],'goods_id'=>$goods_ids])->group('goods_id')->column('sum(stock) as stock','goods_id');
        //更新总库存
        foreach ($goods_ids as $goods_id){
            $total_stock = $goods_total_stock[$goods_id];
            ShopGoods::where(['shop_id'=>$this->shop['id'],'goods_id'=>$goods_id])->update(['total_stock'=>$total_stock]);
        }
        return $this;
    }

    /**
     * @notes 发送通知
     * @return $this
     * @author cjhao
     * @date 2021/9/30 11:49
     */
    public function sendNotice()
    {
        return $this;
    }

    /**
     * @notes 获取订单信息
     * @return mixed
     * @author cjhao
     * @date 2021/9/9 15:22
     */
    public function getOrderInfo()
    {
        return $this->order;
    }

}
