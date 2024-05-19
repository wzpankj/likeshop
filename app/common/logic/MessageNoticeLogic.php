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

namespace app\common\logic;

use app\common\basics\Logic;
use app\common\model\Client_;
use app\common\Enum\NoticeEnum;
use app\common\model\Notice;
use app\common\model\NoticeSetting;
use app\common\model\order\Order;
use app\common\model\order\OrderGoods;
use app\common\model\user\User;
use app\common\server\SmsMessageServer;
use app\common\server\WxMessageServer;



/**
 * 消息通知逻辑
 * Class NoticeLogic
 * @package app\common\logic
 */
class MessageNoticeLogic extends Logic
{
    /**
     * Notes: 根据各个场景发送通知
     * @param $user_id
     * @param $params
     * @author 段誉(2021/4/28 18:21)
     * @throws Exception
     */
    public static function noticeByScene($user_id, $params)
    {
        try {
            $scene_config = NoticeSetting::where('scene', $params['scene'])->find();
            if (empty($scene_config)) {

                throw new \Exception('信息错误');
            }

            $params = self::mergeParams($params);
            $res = false;

            //发送系统消息
            if (isset($scene_config['system_notice']['status']) && $scene_config['system_notice']['status'] == 1) {
                $content = self::contentFormat($scene_config['system_notice']['content'], $params);
                $notice_log = self::addNoticeLog($params, $scene_config,NoticeEnum::SYSTEM_NOTICE, $content);
                if ($notice_log) {
                    $res = true;
                }
            }

            //发送短信记录
            if (isset($scene_config['sms_notice']['status']) && $scene_config['sms_notice']['status'] == 1) {
                $res = (new SmsMessageServer())->send($params);
            }

            //发送公众号记录
            if (isset($scene_config['oa_notice']['status']) && $scene_config['oa_notice']['status'] == 1) {
                $res = (new WxMessageServer($user_id,Client_::oa))->send($params);
            }
            //发送小程序记录
            if (isset($scene_config['mnp_notice']['status']) && $scene_config['mnp_notice']['status'] == 1) {
                $res = (new WxMessageServer($user_id, Client_::mnp))->send($params);
            }

            if (true !== $res) {
                throw new \Exception('发送失败');
            }
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * Notes: 拼装额外参数
     * @param $params
     * @author 段誉(2021/6/22 16:16)
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function mergeParams($params)
    {
        //订单相关信息
        if (!empty($params['params']['order_id'])) {

            $order = Order::where(['id' => $params['params']['order_id']])->find();
            $params['params']['order_sn'] = $order['order_sn'];
            $params['params']['create_time'] = $order['create_time'];
            $params['params']['pay_time'] = date('Y-m-d H:i', $order->getData('pay_time'));
            $params['params']['total_num'] = $order['total_num'];
            $params['params']['order_amount'] = $order['order_amount'];
        }

        //用户相关信息
//        if (!empty($params['params']['user_id'])) {
//            $user = User::where('id', $params['params']['user_id'])->findOrEmpty();
//            $params['params']['nickname'] = $user['nickname'];
//            $params['params']['user_sn'] = $user['sn'];
//        }

        //跳转路径
        $jump_path = self::getPathByScene($params['scene'], $params['params']['order_id'] ?? 0);
        $params['url'] = $jump_path['url'];
        $params['page'] = $jump_path['page'];

        return $params;
    }



    /**
     * Notes: 根据场景获取跳转地址
     * @param $scene
     * @author 段誉(2021/4/27 17:01)
     * @return array
     */
    public static function getPathByScene($scene, $extra_id)
    {
        $page = '/pages/index/index'; // 小程序主页路径
        $url = '/mobile/pages/index/index'; // 公众号主页路径
        if (in_array($scene, NoticeEnum::ORDER_SCENE)) {
            $url = '/mobile/order_detail/order_detail?order_id='.$extra_id;
            $page = '/pages/order_detail/order_detail?order_id='.$extra_id;
        }
        return ['url' => $url, 'page' => $page];
    }


    //格式化消息内容(替换文本)
    public static function contentFormat($content, $params)
    {
        foreach ($params['params'] as $k => $v) {
            $search_replace = '{'.$k.'}';
            $content = str_replace($search_replace, $v, $content);
        }
        return $content;
    }


    //添加通知记录
    public static function addNoticeLog($params, $scene_config, $send_type, $content, $extra = '')
    {
        return Notice::create([
            'user_id' => $params['params']['user_id'] ?? 0,
            'title'   => self::getTitleByScene($send_type, $scene_config),
            'content' => $content,
            'scene'   => $params['scene'],
            'receive_type' => self::getReceiveTypeByScene($params['scene']),
            'send_type'    => $send_type,
            'extra'        => $extra,
            'create_time'  => time()
        ]);
    }

    //更新通知记录
    public static function updateNotice($notice_id, $extra)
    {
        return Notice::where('id', $notice_id)->update(['extra' => $extra]);
    }



    /**
     * Notes: 根据不同发送类型获取标题
     * @param $send_type
     * @param $scene_config
     * @author 段誉(2021/6/23 3:03)
     * @return string
     */
    public static function getTitleByScene($send_type, $scene_config)
    {
        switch ($send_type) {
            case NoticeEnum::SYSTEM_NOTICE:
                $title = $scene_config['system_notice']['title'] ?? '';
                break;
            case NoticeEnum::SMS_NOTICE:
                $title = '';
                break;
            case NoticeEnum::OA_NOTICE:
                $title = $scene_config['oa_notice']['name'] ?? '';
                break;
            case NoticeEnum::MNP_NOTICE:
                $title = $scene_config['mnp_notice']['name'] ?? '';
                break;
            default:
                $title = '';
        }
        return $title;
    }


    /**
     * Notes: 根据不同场景返回当前接收对象
     * @param $scene
     * @author 段誉(2021/6/23 3:02)
     * @return int
     */
    public static function getReceiveTypeByScene($scene)
    {
        //通知平台
        if (in_array($scene, NoticeEnum::NOTICE_PLATFORM_SCENE)) {
            return NoticeEnum::NOTICE_PLATFORM;
        }

        //通知商家
        if (in_array($scene, NoticeEnum::NOTICE_SHOP_SCENE)) {
            return NoticeEnum::NOTICE_SHOP;
        }

        //通知会员
        if (in_array($scene, NoticeEnum::NOTICE_USER_SCENE)) {
            return NoticeEnum::NOTICE_USER;
        }

    }

}