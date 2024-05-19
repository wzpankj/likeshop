<?php

namespace app\admin\logic\setting;

use app\common\basics\Logic;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;

/**
 * 网站基础设置
 * Class BasicLogic
 * @package app\admin\logic\setting
 */
class BasicLogic extends Logic
{

    /**
     * Notes: 网站设置
     * @author 段誉(2021/6/10 23:52)
     * @return array
     */
    public static function getBasicConfig()
    {
        $config = [
            'file_url'          => UrlServer::getFileUrl(''),
            'shop_name'         => ConfigServer::get('website', 'shop_name'),
            'shop_logo'         => ConfigServer::get('website', 'shop_logo'),
            'web_favicon'       => ConfigServer::get('website', 'web_favicon'),
            'user_image'        => ConfigServer::get('website', 'user_image'),

            'shop_login_image'      => ConfigServer::get('website_shop', 'shop_login_image'),
            'shop_login_title'      => ConfigServer::get('website_shop', 'shop_login_title'),

            'platform_login_image'  => ConfigServer::get('website_platform', 'platform_login_image'),
            'platform_login_title'  => ConfigServer::get('website_platform', 'platform_login_title'),
            'platform_contact'      => ConfigServer::get('website_platform', 'platform_contact'),
            'platform_mobile'       => ConfigServer::get('website_platform', 'platform_mobile'),

        ];
        return $config;
    }


    /**
     * Notes: 网站设置-商城设置
     * @param $post
     * @author 段誉(2021/6/10 23:53)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setWebsiteBasic($post)
    {
        ConfigServer::set('website', 'shop_name', $post['shop_name']);
        ConfigServer::set('website', 'shop_logo', UrlServer::setFileUrl($post['shop_logo'] ?? ''));
        ConfigServer::set('website', 'web_favicon', UrlServer::setFileUrl($post['web_favicon'] ?? ''));
        ConfigServer::set('website', 'user_image', UrlServer::setFileUrl($post['user_image'] ?? ''));
    }

    /**
     * Notes: 网站设置-平台设置
     * @param $post
     * @author 段誉(2021/6/10 23:53)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setPlatform($post)
    {
        ConfigServer::set('website_platform', 'platform_login_image', UrlServer::setFileUrl($post['platform_login_image'] ?? ''));
        ConfigServer::set('website_platform', 'platform_login_title', $post['platform_login_title']);
        ConfigServer::set('website_platform', 'platform_contact', $post['platform_contact']);
        ConfigServer::set('website_platform', 'platform_mobile', $post['platform_mobile']);
    }


    /**
     * Notes: 网站设置-门店设置
     * @param $post
     * @author 段誉(2021/6/10 23:53)
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setShop($post)
    {
        ConfigServer::set('website_shop', 'shop_login_image', UrlServer::setFileUrl($post['shop_login_image'] ?? ''));
        ConfigServer::set('website_shop', 'shop_login_title', $post['shop_login_title']);
    }

}