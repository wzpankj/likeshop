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
 * 售后退款相关 枚举类型
 * Class AfterSaleEnum
 * @Author ISH
 * @package app\common\enum
 */
class AfterSaleEnum
{
    //售后状态status
    const STATUS_ING = 0;//-申请退款
    const STATUS_MECHANT_REFUSED = 1;//商家拒绝
    const STATUS_GOODS_RETURNED = 2;//商品待退货
    const STATUS_RECEIVE_GOODS = 3;//商家待收货
    const STATUS_MECHANT_REFUSED_GOODS = 4;//商家拒收货
    const STATUS_WAITING = 5;//等待退款
    const STATUS_COMPLETE = 6;//退款成功
   
}