<?php
namespace app\api\controller;

use app\common\basics\Api;
use app\common\server\ConfigServer;
use app\common\server\JsonServer;
use app\common\server\UrlServer;
use app\common\model\shop\Shop;

class Setting extends Api
{
    public $like_not_need_login = ['getPlatformCustomerService', 'getShopCustomerService'];

    /**
     * 平台客服
     */
    public function getPlatformCustomerService()
    {
        $image = ConfigServer::get('customer_service', 'image', '');
        $image = $image ? UrlServer::getFileUrl($image) : '';
        $config = [
            'wechat' => ConfigServer::get('customer_service', 'wechat', ''),
            'phone' => ConfigServer::get('customer_service', 'phone', ''),
            'business_time' => ConfigServer::get('customer_service', 'business_time', ''),
            'image' => $image
        ];
        return JsonServer::success('', $config);
    }

}
