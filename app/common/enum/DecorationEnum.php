<?php
namespace app\common\enum;

class DecorationEnum
{
    // 配色效果
    const EFFECT = [
        'green_theme' => '/static/common/image/default/green_theme.png',
        'brown_theme' => '/static/common/image/default/brown_theme.png',
        'blue_theme' => '/static/common/image/default/blue_theme.png',
        'red_theme' => '/static/common/image/default/red_theme.png',
        'bamboo_theme' => '/static/common/image/default/bamboo_theme.png'
    ];

    // 风格配色
    const STYLE = [
        'green_theme' => [
            'title' => '默认',
            'image' => '/static/common/image/default/green_theme_icon.png'
        ],
        'brown_theme' => [
            'title' => '棕色',
            'image' => '/static/common/image/default/brown_theme_icon.png'
        ],
        'blue_theme' => [
            'title' => '天蓝色',
            'image' => '/static/common/image/default/blue_theme_icon.png'
        ],
        'red_theme' => [
            'title' => '经典红',
            'image' => '/static/common/image/default/red_theme_icon.png'
        ],
        'bamboo_theme' => [
            'title' => '竹绿色',
            'image' => '/static/common/image/default/bamboo_theme_icon.png'
        ],
    ];

    //链接类型
    const MALL_MODULE = 1;//商城模块
    const CUSTOM_LINK = 2;//自定义链接

    /**
     * @notes 连接类型
     * @param bool $type
     * @return string|string[]
     * @author ljj
     * @date 2021/10/9 4:42 下午
     */
    public static function getLinkType($type = true)
    {
        $desc = [
            self::MALL_MODULE      => '商城模块',
            self::CUSTOM_LINK      => '自定义链接',
        ];
        if ($type === true) {
            return $desc;
        }
        return $desc[$type] ?? '未知';
    }
}