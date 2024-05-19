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

namespace app\common\model;


use app\common\basics\Models;
use app\common\server\UrlServer;

/**
 * 文件模型
 * Class File
 * @package app\common\model
 */
class File extends Models
{
    /**
     * NOTE: 获取器,修改图片路径
     * @author: 张无忌
     * @param $value
     * @return string
     */
    public static function getUriAttr($value)
    {
        return $value ? UrlServer::getFileUrl($value) : '';
    }
}