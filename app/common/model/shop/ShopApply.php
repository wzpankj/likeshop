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

namespace app\common\model\shop;


use app\common\basics\Models;
use app\common\server\UrlServer;

/**
 * 商家申请入驻模型
 * Class ShopApply
 * @package app\common\model\shop
 */
class ShopApply extends Models
{
    /**
     * NOTE: 关联主营类目模型
     * @author 张无忌
     */
    public function category()
    {
        return $this->hasOne('ShopCategory', 'id', 'cid');
    }

    /**
     * NOTE: 获取器-申请时间
     * @param $value
     * @return false|string
     * @author: 张无忌
     */
    public function getApplyTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * @Notes: 修改器-营业执照
     * @Author: 张无忌
     * @param $value
     */
//    public function setLicenseAttr($value)
//    {
//        foreach ($value as &$url) {
//            UrlServer::setFileUrl($url);
//        }
//    }

    /**
     * @Notes: 获取器-营业执照
     * @Author: 张无忌
     * @param $value
     * @return array
     */
    public function getLicenseAttr($value)
    {
        $result = $value ? explode(',', $value) : [];

        foreach ($result as &$url) {
            UrlServer::getFileUrl($url);
        }

        return $result;
    }

    public function getAuditStatusDescAttr($value)
    {
        $desc = [1=>'待审核',2=>'审核通过',3=>'审核拒绝'];
        return $desc[$value];
    }
}