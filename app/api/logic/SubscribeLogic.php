<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------

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

namespace app\api\logic;
use app\common\basics\Logic;
use app\common\enum\OrderEnum;
use app\common\model\NoticeSetting;


/**
 * 订阅通知逻辑层
 * Class SubscribeLogic
 * @package app\api\logic
 */
class SubscribeLogic extends Logic
{
    public static function lists($order_type)
    {
        //获取订阅通知
        if(OrderEnum::ORDER_TYPE_TAKE_AWAY == $order_type){
            return [];
        }

        $where = [
            ['mnp_notice', '<>', ''],
            ['type', '=', 1]
        ];
        $lists = NoticeSetting::where($where)->field('mnp_notice')->limit(3)->select()->toArray();
        $template_id_list = [];

        foreach ($lists as $item) {
            if (isset($item['mnp_notice']['status']) && $item['mnp_notice']['status'] != 1) {
                continue;
            }
            $template_id_list[] = $item['mnp_notice']['template_id'] ?? '';
        }
        return $template_id_list;
    }
}