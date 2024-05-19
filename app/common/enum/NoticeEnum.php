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
 * Class NoticeEnum
 * @package app\common\enum
 */
class NoticeEnum
{
    //通知类型
    const SYSTEM_NOTICE     = 1; //系统通知
    const SMS_NOTICE        = 2; //短信通知
    const OA_NOTICE         = 3; //公众号模板通知
    const MNP_NOTICE        = 4; //小程序订阅消息通知


    //通知对象
    const NOTICE_USER       = 1; //通知会员
    const NOTICE_SHOP       = 2; //通知门店
    const NOTICE_PLATFORM   = 3; //通知平台

    //*************************************************通知会员********************************************************
    //通知会员-取餐通知
    const TAKEFOOD_NOTICE   = 100;        //取餐通知

    //通知门店-外卖订单
    const TAKEOUT_NOTICE    = 200;       //外卖订单通知


    //订单相关场景
    const ORDER_SCENE = [
        self::TAKEFOOD_NOTICE,
//        self::TAKEOUT_NOTICE,
    ];
    //验证码场景
    const NOTICE_NEED_CODE = [];
    //通知门店
    const NOTICE_SHOP_SCENE = [
        self::TAKEOUT_NOTICE,
    ];
    //通知平台
    const NOTICE_PLATFORM_SCENE = [];
    //通知用户
    const NOTICE_USER_SCENE = [
        self::TAKEFOOD_NOTICE,
    ];

    //场景值-短信场景
    const SMS_SCENE = [
        'DMDDTZ' => self::TAKEOUT_NOTICE, //外卖订单通知
    ];


    /**
     * @notes 获取通知类型
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/10/9 10:04
     */
    public static function getNoticeType($from = true)
    {
        $desc = [
            self::NOTICE_USER   => '通知用户',
            self::NOTICE_SHOP   => '通知门店',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';

    }
    /**
     * Notes: 通知描述
     * @param $state
     * @author 段誉(2021/6/4 15:14)
     * @return array|mixed|string
     */
    public static function getSceneDesc($state)
    {
        $data = [
            //会员-短信验证码
            self::TAKEFOOD_NOTICE      => '取餐通知',
            //商家
            self::TAKEOUT_NOTICE       => '外卖订单通知',
        ];
        if ($state === true) {
            return $data;
        }
        return $data[$state] ?? '';
    }

}