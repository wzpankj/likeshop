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

namespace app\api\controller;


use app\api\logic\SubscribeLogic;
use app\common\basics\Api;
use app\common\enum\OrderEnum;
use app\common\server\JsonServer;

/**
 * 订阅通知
 * Class Subscribe
 * @package app\api\controller
 */
class Subscribe extends Api
{
    public $like_not_need_login = ['lists'];

    public function lists()
    {
        //订单类型
        $order_type = $this->request->get('order_type',OrderEnum::ORDER_TYPE_YOUSELF_TAKE);
        $lists = SubscribeLogic::lists($order_type);
        return JsonServer::success('获取成功', $lists);
    }
}