<?php
namespace app\api\logic;

use app\common\basics\Logic;
use app\common\model\DecorateBottom;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;


class IndexLogic extends Logic
{
    /**
     * @notes 通用配置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/13 10:39 上午
     */
    public static function config()
    {
        //底部导航
        $navigation = DecorateBottom::field('name as text,select_icon as selectedIconPath,icon as iconPath,color,select_color as selectColor')
            ->where('del', 0)
            ->order('id', 'asc')
            ->select()
            ->toArray();

        //小程序分享
        $share_mnp = ConfigServer::get('share', 'mnp', [
            'mnp_share_title' => ''
        ]);

        //个人中心背景图
        $center_top_bg = ConfigServer::get('decoration_center', 'background_image', '');
        if (!empty($center_top_bg)) {
            $center_top_bg = UrlServer::getFileUrl($center_top_bg);
        }

        //移动端登录页logo
        $shop_logo = ConfigServer::get('website', 'shop_logo');
        //浏览器标签图标
        $web_favicon = ConfigServer::get('website', 'web_favicon');

        $config = [
            'shop_login_logo'   => empty($shop_logo) ? '' : UrlServer::getFileUrl($shop_logo),//移动端登录页logo
            'web_favicon'       => empty($web_favicon) ? '' : UrlServer::getFileUrl($web_favicon),//浏览器标签图标
            'name'              => ConfigServer::get('website', 'shop_name'),//商城名称
            'copyright_info'    => ConfigServer::get('copyright', 'company_name'),//版权信息
            'icp_number'        => ConfigServer::get('copyright', 'number'),//ICP备案号
            'icp_link'          => ConfigServer::get('copyright', 'link'),//备案号链接
            'center_setting'    => [ // 个人中心设置
              // 顶部背景图
              'top_bg_image'    => $center_top_bg
            ],
            // 分享设置
            'share'             => array_merge($share_mnp),
            // 首页底部导航菜单
            'navigation_menu'   => $navigation,
            //充值入口是否开启
            'recharge_status'   => ConfigServer::get('recharge', 'app_status', '0'),
            //腾讯地图key
            'map_key'           => ConfigServer::get('map','qq_map_key',''),
        ];
        return $config;
    }

    /**
     * @notes 商城风格
     * @return array|int|mixed|string|null
     * @author ljj
     * @date 2021/10/13 2:23 下午
     */
    public static function style()
    {
        return ['theme' => ConfigServer::get('decoration', 'theme', 'green_theme')];
    }
}
