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
namespace app\shop\logic;
use app\common\server\ConfigServer;

/**
 * 通知设置逻辑层
 * Class NoticeSettingLogic
 * @package app\shop\logic\NoticeSettingLogic
 */
class NoticeSettingLogic {


    /**
     * @notes 获取通知配置
     * @param $shop_id
     * @return array
     * @author cjhao
     * @date 2021/10/11 11:09
     */
    public static function getNoticeConfig($shop_id){
        $config = [
            'take_out_notice'   => ConfigServer::get('shop_notice','take_out_notice',0,$shop_id),
        ];
        return $config;
    }

    /**
     * @notes 设置通知配置
     * @param $post
     * @param $shop_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/10/11 11:10
     */
    public static function setNoticeConfig($post,$shop_id)
    {
        ConfigServer::set('shop_notice','take_out_notice',$post['take_out_notice'],$shop_id);
    }
}
