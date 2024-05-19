<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------
namespace app\common\enum;

class MenuEnum
{
    //首页菜单
    const INDEX = [
        //门店自提
        [
            'index'         =>  100,
            'name'          => '门店自提',
            'link'          => '/bundle/pages/store_list/store_list?type=1',
            'is_tab'        => 0,
            'link_type'     => 1,
        ],
        //外卖配送
        [
            'index'         =>  101,
            'name'          => '外卖配送',
            'link'          => '/bundle/pages/store_list/store_list?type=2',
            'is_tab'        => 0,
            'link_type'     => 1,
        ],
    ];

    //个人中心菜单
    const CENTRE = [
        [
            'index'         => 201,
            'name'          => '个人资料',
            'link'          => '/bundle/pages/user_profile/user_profile',
            'is_tab'        => 0,
            'link_type'     => 1,
        ],
        [
            'index'         => 202,
            'name'          => '收货地址',
            'link'          => '/bundle/pages/user_address/user_address',
            'is_tab'        => 0,
            'link_type'     => 1,
        ],
        [
            'index'         => 203,
            'name'          => '联系客服',
            'link'          => '/bundle/pages/service/service',
            'is_tab'        => 0,
            'link_type'     => 1,
            'menu_type'     => 1,
        ],
        [
            'index'         => 204,
            'name'          => '我的优惠券',
            'link'          => '/bundle/pages/user_coupon/user_coupon',
            'is_tab'        => 0,
            'link_type'     => 1,
            'menu_type'     => 1,
        ]
    ];

    /**
     * Notes:获取菜单列表
     * @param bool $scene 指定个人或首页菜单：true时返回所有菜单
     * @param bool $from 返回某个菜单:true返回个人菜单或首页菜单
     * @return array
     * @author: cjhao 2021/5/15 16:51
     * name         => 菜单名称
     * link         => 调整链接
     * is_tab       => 是否的tab页
     * link_type    => 菜单类型：1-跳转；2-web-view；3-按钮（微信小程序可调用客服）
     */
    public static function getMenu($scene = true,$from = true)
    {
        //首页菜单
        $config_index = self::INDEX;

        //个人菜单
        $config_center = self::CENTRE;

        $config_name = 'config_'.$scene;
        $content = $$config_name;
        if(true === $scene){
            $content = array_merge($config_index,$config_center);
        }
        if(true === $from){
            return $content;
        }

        $menu_index = array_column($content,'index');
        $key = array_search($from,$menu_index);
        if(false !== $key){
            return $content[$key];
        }
        return [];
    }
}