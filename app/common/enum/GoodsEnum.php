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
 * 商品枚举
 * Class GoodsEnum
 * @package app\common\enum
 */
class GoodsEnum
{
    /**
     * 运费类型
     */
    const EXPRESS_TYPE_FREE         = 1; //包邮
    const EXPRESS_TYPE_UNIFIED      = 2; //统一运费
    const EXPRESS_TYPE_TEMPLATE     = 3; //运费模板


    /**
     * 销售状态
     */
    const STATUS_SOLD_OUT   = 0;  //仓库中
    const STATUS_SHELVES    = 1;  //上架中

    /**
     * 删除状态
     */
    const DEL_NORMAL = 0; // 正常
    const DEL_TRUE = 1; // 已删除
    const DEL_RECYCLE = 2; // 回收站


    /**
     * 审核状态
     */
    const AUDIT_STATUS_STAY    = 0; //待审核
    const AUDIT_STATUS_OK      = 1; //审核通过
    const AUDIT_STATUS_REFUSE  = 2; //审核失败

}