<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\admin\controller;


use app\admin\logic\AnnouncementLogic;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

/**
 * 门店公告控制器
 * Class Announcement
 * @package app\admin\controller
 */
class Announcement extends AdminBase
{
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            AnnouncementLogic::edit($post);
            return JsonServer::success('操作成功');

        }

        return view('',['detail'=>AnnouncementLogic::getDetail()]);
    }
}