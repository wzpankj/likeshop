<?php
namespace app\shop\logic\decoration;

use app\common\basics\Logic;
use app\common\model\shop\Shop;
use app\common\enum\ShopEnum;
use app\common\server\UrlServer;

class IndexLogic extends Logic
{
    public static function getShopSet($shop_id)
    {
        $shop = Shop::field('logo,background')->findOrEmpty($shop_id)->toArray();
        $shop['logo'] = $shop['logo'] ?  UrlServer::getFileUrl($shop['logo']) : UrlServer::getFileUrl(ShopEnum::DEFAULT_LOGO);
        $shop['background'] = $shop['background'] ? UrlServer::getFileUrl($shop['background']) : UrlServer::getFileUrl(ShopEnum::DEFAULT_BG);
        return $shop;
    }

    public static function set($post)
    {
        try{
            $update = [
                'logo' => clearDomain($post['logo']),
                'background' => clearDomain($post['background']),
                'update_time' => time()
            ];
            $where = [
                'id' => $post['shop_id'],
                'del' => 0
            ];
            Shop::where($where)->update($update);
            return true;
        }catch(\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }
}