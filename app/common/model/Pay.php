<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------


namespace app\common\model;

use app\common\enum\PayEnum;
use app\common\server\UrlServer;
use think\Model;

/**
 * 支付
 * Class Pay
 * @package app\common\model
 */
class Pay extends Model
{
    protected $name = 'dev_pay';

    // 设置json类型字段
    protected $json = ['config'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    //图片路径
    public function getIconAttr($value, $data)
    {
        return UrlServer::getFileUrl($value);
    }

    //图片路径
    public function getImageAttr($value,$data){
        return UrlServer::getFileUrl($value);
    }


    //支付设置状态
    public function getStatusTextAttr($value, $data)
    {
        if ($data['status'] == 1){
            return '启用';
        }
        return '关闭';
    }
    
}