<?php


namespace app\shop\logic;


use app\common\basics\Logic;
use app\common\enum\ShopEnum;
use app\common\model\shop\Shop;

class StoreLogic extends Logic
{
    /**
     * @Notes: 获取商家详细
     * @Author: 张无忌
     * @param $shop_id
     * @return array
     */
    public static function detail($shop_id)
    {
        $model = new Shop();
        $detail = $model->field(true)
            ->with(['category'])
            ->findOrEmpty($shop_id)->toArray();

        $detail['category'] = $detail['category']['name'] ?? '未知';
        $detail['type'] = ShopEnum::getShopTypeDesc($detail['type']);

        return $detail;
    }

    /**
     * @Notes: 修改商家信息
     * @Author: 张无忌
     * @param $post
     * @return bool
     */
    public static function edit($post)
    {
        try {
            Shop::update([
                'nickname'       => $post['nickname'],
                'mobile'         => $post['mobile'],
                'keywords'       => $post['keywords'] ?? '',
                'intro'          => $post['intro'] ?? '',
                'is_run'         => $post['is_run'],
//                'service_mobile' => $post['service_mobile'],
                'weekdays'       => $post['weekdays'] ?? '',
                'province_id'    => $post['province_id'] ?? 0,
                'city_id'        => $post['city_id'] ?? 0,
                'district_id'    => $post['district_id'] ?? 0,
                'address'        => $post['address'] ?? '',
                'longitude'      => $post['longitude'] ?? '',
                'latitude'       => $post['latitude'] ?? '',
                'refund_address' => json_encode([
                    'nickname'    => $post['refund_nickname'],
                    'mobile'      => $post['refund_mobile'],
                    'province_id' => $post['refund_province_id'],
                    'city_id'     => $post['refund_city_id'],
                    'district_id' => $post['refund_district_id'],
                    'address'     => $post['refund_address'],
                ], JSON_UNESCAPED_UNICODE)
            ], ['id'=>$post['id']]);

            return true;
        } catch (\Exception $e) {
            static::$error = $e->getMessage();
            return false;
        }
    }
}