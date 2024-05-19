<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\logic;

use app\common\basics\Logic;
use app\common\enum\NoticeEnum;
use app\common\model\Notice;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;

/**
 * 系统通知
 * Class NoticeLogic
 * @package app\api\logic
 */
class SystemNoticeLogic extends Logic
{
    /**
     * Notes: 消息主页
     * @param $user_id
     * @author 段誉(2021/6/22 1:18)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function index($user_id)
    {
        //最新系统消息
        $server = Notice::where([
            ['user_id', '=', $user_id],
            ['send_type', '=', NoticeEnum::SYSTEM_NOTICE],
            ['scene', 'not in', [NoticeEnum::GET_EARNINGS_NOTICE, NoticeEnum::GET_FUTURE_EARNINGS_NOTICE]],
        ])->order('id desc')->find();

        //最新收益通知
        $earning = Notice::where([
            ['user_id', '=', $user_id],
            ['send_type', '=', NoticeEnum::SYSTEM_NOTICE],
            ['scene', 'in', [NoticeEnum::GET_EARNINGS_NOTICE, NoticeEnum::GET_FUTURE_EARNINGS_NOTICE]],
        ])->order('id desc')->find();

        $data['system'] = [
            'title' => '系统通知',
            'content' => $server['content'] ?? '暂无系统消息',
            'img' => UrlServer::getFileUrl(ConfigServer::get('website', 'system_notice')),
            'type' => 'system',
        ];
        $data['earning'] = [
            'title' => '收益通知',
            'content' => $earning['content'] ?? '暂无收益消息',
            'img' => UrlServer::getFileUrl(ConfigServer::get('website', 'earning_notice')),
            'type' => 'earning',
        ];
        $res = array_values($data);
        return $res;
    }



    /**
     * Notes: 消息列表
     * @param $user_id
     * @param $type
     * @param $page
     * @param $size
     * @author 段誉(2021/6/22 1:18)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists($user_id, $type, $page, $size)
    {
        $where = [];
        $where[] = ['user_id', '=', $user_id];
        $where[] = ['send_type', '=', NoticeEnum::SYSTEM_NOTICE];

        if ($type == 'earning') {
            $where[] = ['scene', 'in', [NoticeEnum::GET_EARNINGS_NOTICE, NoticeEnum::GET_FUTURE_EARNINGS_NOTICE]];
        } else {
            $where[] = ['scene', 'not in', [NoticeEnum::GET_EARNINGS_NOTICE, NoticeEnum::GET_FUTURE_EARNINGS_NOTICE]];
        }

        $count = Notice::where($where)->count();
        $lists = Notice::where($where)
            ->order('id desc')
            ->page($page, $size)
            ->select();

        //更新为已读
        Notice::where($where)
            ->where('read', '<>', 1)
            ->update(['read' => 1]);

        return [
            'list' => $lists,
            'page' => $page,
            'size' => $size,
            'count' => $count,
            'more' => is_more($count, $page, $size)
        ];
    }


    /**
     * Notes: 是否有未读的消息
     * @param $user_id
     * @author 段誉(2021/6/22 1:17)
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function unRead($user_id)
    {
        $un_read = Notice::where([
            'user_id' => $user_id,
            'read' => 0,
            'send_type' => NoticeEnum::SYSTEM_NOTICE
        ])->find();
        if ($un_read) {
            return true;
        }
        return false;
    }
}