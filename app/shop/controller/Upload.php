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

namespace app\shop\controller;


use app\common\basics\ShopBase;
use app\common\server\FileServer;
use app\common\server\JsonServer;
use Exception;

class Upload extends ShopBase
{
//    public $like_not_need_login = ['image'];

    /**
     * NOTE: 上传图片
     * @author: 张无忌
     */
    public function image()
    {
        try {

            $cid = $this->request->post('cid');
            $result = FileServer::image($cid, $this->shop_id);

            return JsonServer::success("上传成功", $result);
        } catch (Exception $e) {
            return JsonServer::error($e->getMessage());
        }
    }

    /**
     * 上传视频
     */
    public function video()
    {
        try {
            $cid = $this->request->post('cid');
            $result = FileServer::video($cid, $this->shop_id);

            return JsonServer::success("上传成功", $result);
        } catch (Exception $e) {
            return JsonServer::error($e->getMessage());
        }
    }
}