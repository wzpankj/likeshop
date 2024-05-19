<?php
namespace app\api\logic;

use app\common\basics\Logic;
use app\common\model\DecorateHome;
use app\common\model\DecorateMine;
use app\common\enum\MenuEnum;

class MenuLogic extends Logic
{
    public static function getMenu($type)
    {
        switch ($type) {
            case 1:
                $lists = DecorateHome::select()->toArray();
                foreach ($lists as $list) {
                    $menu_content = MenuEnum::getMenu('index', $list['link_address']);

                    //处理图标
                    $menu_list[] = [
                        'name' => $list['name'],
                        'image' => $list['image'],
                        'link' => $menu_content['link'] ?? $list['link_address'],
                        'is_tab' => $menu_content['is_tab'] ?? '',
                        'link_type' => $menu_content['link_type'] ?? '',
                        'describe' => $list['describe'],
                    ];
                }
                break;
            case 2:
                $lists = DecorateMine::where(['is_show'=>1,'del'=>0])->select()->toArray();
                foreach ($lists as $list) {
                    $menu_content = MenuEnum::getMenu('center', $list['link_address']);

                    //处理图标
                    $menu_list[] = [
                        'name' => $list['name'],
                        'image' => $list['image'],
                        'link' => $menu_content['link'] ?? $list['link_address'],
                        'is_tab' => $menu_content['is_tab'] ?? '',
                        'link_type' => $menu_content['link_type'] ?? $list['link_type'],
                    ];
                }
                break;
            default:
                $lists = [];
        }

        return $menu_list;
    }
}