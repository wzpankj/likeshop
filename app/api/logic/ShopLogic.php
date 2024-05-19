<?php
namespace app\api\logic;

use app\common\{basics\Logic,
    enum\CouponEnum,
    enum\DefaultConfigEnum,
    enum\ShopEnum,
    model\Announcement,
    model\marketing\Coupon,
    model\shop\Shop,
    server\ConfigServer};



/**
 * 门店列表
 * Class ShopLogic
 * @package app\api\logic
 */
class ShopLogic extends Logic
{
    /**
     * @notes 门店列表
     * @param $get
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/1 11:51
     */
    public static function lists($get,$page_no,$page_size):array
    {
        $new = time();
        $where[] = ['del','=',0];
        $where[] = ['status','=',1];
        $type = $get['type'] ?? ShopEnum::DELIVERY_PICK_UP;


        //扫码进来店铺点餐页面
        if (isset($get['shop_id_s']) && $get['shop_id_s']) {
            $where[] = ['id','=',intval($get['shop_id_s'])];
        }

        if($type){
            $where[] = ['delivery_type','find in set',$type];
        }
        if(isset($get['keyword']) && $get['keyword']){
            $where[] = ['name','like','%'.$get['keyword'].'%'];
        }
        //用st_distance_sphere函数计算两点记录，单位米，这里换成千米
        $field = 'id,name,phone,longitude,latitude,business_start_time,business_end_time,status,round(st_distance_sphere(point('.$get['longitude'].','.$get['latitude'].'),point(longitude, latitude))/1000,2) as distance,province_id,city_id,district_id,address,delivery_type,weekdays';
        $list = Shop::where($where)
                ->append(['province_name','city_name','district_name'])
                ->field($field)
                ->order('distance asc')
                ->page($page_no,$page_size)
                ->select();

        $count = Shop::where($where)
                ->count();
        $shop_coupon_list = Coupon::where(['status'=>CouponEnum::COUPON_STATUS_CONDUCT])->column('shop_id');

        foreach ($list as $shop){
            $shop->status = $shop['status'];
            if(!in_array(date('N'),explode(',',$shop['weekdays']))){
                $shop->status = 0;
            }
            $business_start_time = strtotime(date('Y-m-d '.$shop['business_start_time']));
            $business_end_time   = strtotime(date('Y-m-d '.$shop['business_end_time']));
            #营业到次日
            if ($shop['business_start_time']>$shop['business_end_time']) {
                $business_end_time   = strtotime(date('Y-m-d '.$shop['business_end_time']))+86400;
            }
            /*var_dump(date('Y-m-d H:i',$business_start_time));
            var_dump(date('Y-m-d H:i',$business_end_time));
            exit;*/
            if($business_start_time > $new || $business_end_time < $new){
                $shop->status = 0;
            }
            $shop->business = $shop->business_start_time.'-'.$shop->business_end_time;
            $shop->distance = round($shop->distance,2).'km';
            $shop->status_desc = DefaultConfigEnum::getShopStatusDesc($shop->status);
            $shop->hidden(['business_start_time','business_end_time']);
            $shop->address_detail = $shop['province_name'].$shop['city_name'].$shop['district_name'].$shop['address'];
            $shop->delivery_type = explode(',',$shop->delivery_type);
           
            $shop->have_coupon = in_array($shop->id,$shop_coupon_list);
        }
        $more = is_more($count,$page_no,$page_size);

        return [
            'count' =>  $count,
            'list'  =>  $list,
            'more'  =>  $more
        ];

    }

    /**
     * @notes 门店公告
     * @return string[]
     * @author ljj
     * @date 2021/10/19 6:22 下午
     */
    public static function announcement()
    {
        $announcement = Announcement::where(['del'=>0])->findOrEmpty();
        if ($announcement->isEmpty()) {
            $announcement['content'] = '';
        }

        return ['content'=>$announcement['content']];
    }

    /**
     * @notes 门店列表--改成单店铺辅助扫码点餐
     * @param $get
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/1 11:51
     */
    public static function listsone($get,$page_no,$page_size):array
    {
        $new = time();
        $where[] = ['del','=',0];
        $type = $get['type'] ?? ShopEnum::DELIVERY_PICK_UP;
        if($type){
            $where[] = ['delivery_type','find in set',$type];
        }
        if(isset($get['keyword']) && $get['keyword']){
            $where[] = ['name','like','%'.$get['keyword'].'%'];
        }
        //用st_distance_sphere函数计算两点记录，单位米，这里换成千米
        $field = 'id,name,phone,longitude,latitude,business_start_time,business_end_time,status,round(st_distance_sphere(point('.$get['longitude'].','.$get['latitude'].'),point(longitude, latitude))/1000,2) as distance,province_id,city_id,district_id,address,delivery_type,weekdays';
        $list = Shop::where($where)
                ->append(['province_name','city_name','district_name'])
                ->field($field)
                ->order('distance asc')
                ->page($page_no,$page_size)
                ->select();

        $count = Shop::where($where)
                ->count();
        $shop_coupon_list = Coupon::where(['status'=>CouponEnum::COUPON_STATUS_CONDUCT])->column('shop_id');

        foreach ($list as $shop){
            $shop->status = $shop['status'];
            if(!in_array(date('N'),explode(',',$shop['weekdays']))){
                $shop->status = 0;
            }
            $business_start_time = strtotime(date('Y-m-d '.$shop['business_start_time']));
            $business_end_time   = strtotime(date('Y-m-d '.$shop['business_end_time']));
            #营业到次日
            if ($shop['business_start_time']>$shop['business_end_time']) {
                $business_end_time   = strtotime(date('Y-m-d '.$shop['business_end_time']))+86400;
            }
            if($business_start_time > $new || $business_end_time < $new){
                $shop->status = 0;
            }
            $shop->business = $shop->business_start_time.'-'.$shop->business_end_time;
            $shop->distance = round($shop->distance,2).'km';
            $shop->status_desc = DefaultConfigEnum::getShopStatusDesc($shop->status);
            $shop->hidden(['business_start_time','business_end_time']);
            $shop->address_detail = $shop['province_name'].$shop['city_name'].$shop['district_name'].$shop['address'];
            $shop->delivery_type = explode(',',$shop->delivery_type);
           
            $shop->have_coupon = in_array($shop->id,$shop_coupon_list);
        }
        $more = is_more($count,$page_no,$page_size);

        return [
            'count' =>  $count,
            'list'  =>  $list,
            'more'  =>  $more
        ];

    }
}