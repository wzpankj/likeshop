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

/**
 * 广告枚举
 * Class AdEnum
 * @package app\common\enum
 */
class AdEnum
{
    //渠道
    const MNP = 1;//小程序

    //广告位类型
    const TYPE_DEFAULT = 0;//系统默认
    const TYPE_CUSTOM = 1;//自定义


    /**
     * Notes:获取终端
     * @param bool $from
     * @return array|mixed
     * @author: cjhao 2021/4/19 11:32
     */
    public static function getTerminal($from = true){
        $desc = [
            self::MNP    => '小程序',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from];
    }

    /**
     * @notes 广告位类型
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2021/10/11 6:04 下午
     */
    public static function getType($from = true){
        $desc = [
            self::TYPE_DEFAULT    => '系统默认',
            self::TYPE_CUSTOM    => '自定义',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from];
    }
}