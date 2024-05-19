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

namespace app\admin\logic;

use app\common\basics\Logic;
use app\common\model\Pay;

/**
 * 支付配置 - 逻辑
 * Class PayConfigLogic
 * @package app\admin\logic
 */
class PayConfigLogic extends Logic
{

    /**
     * Notes: 配置列表
     * @author 段誉(2021/5/7 18:15)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists()
    {
        $count = Pay::count();
        $lists = Pay::withAttr('status', function($value, $data) {
            return $value == 1 ? '启用' : '关闭';
        })->order('sort')->select();
        return ['lists' => $lists, 'count' => $count];
    }


    /**
     * Notes: 详情
     * @param $pay_code
     * @author 段誉(2021/5/7 18:15)
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function info($pay_code)
    {
        return Pay::where(['code' => $pay_code])->find();
    }


    /**
     * Notes: 余额
     * @param $post
     * @author 段誉(2021/5/7 18:15)
     * @return Pay
     */
    public static function editBalance($post)
    {
        return Pay::where('code', 'balance')->update([
            'short_name' => $post['short_name'],
            'image'      => $post['image']  ?? '',
            'status'     => $post['status'],
            'sort'       => $post['sort'] ?? 0,
        ]);
    }


    /**
     * Notes: 微信
     * @param $post
     * @author 段誉(2021/5/7 18:16)
     * @return Pay
     */
    public static function editWechat($post)
    {

        $data = [
            'short_name' => $post['short_name'],
            'image'      => $post['image'] ?? '',
            'status'     => $post['status'],
            'sort'       => $post['sort'] ?? 0,
            'config'     => [
                'pay_sign_key' => $post['pay_sign_key'],
                'mch_id' => $post['mch_id'],
                'apiclient_cert' => $post['apiclient_cert'],
                'apiclient_key' => $post['apiclient_key']
            ]
        ];
        return Pay::where('code', 'wechat')->update($data);
    }


    /**
     * Notes: 支付宝
     * @param $post
     * @author 段誉(2021/5/7 18:16)
     * @return Pay
     */
    public static function editAlipay($post)
    {
        $data = [
            'short_name' => $post['short_name'],
            'image'      => $post['image'] ?? '',
            'status'     => $post['status'],
            'sort'       => $post['sort'] ?? 0,
            'config'     => [
                'app_id' => $post['app_id'],
                'private_key' => $post['private_key'],//应用私钥
                'ali_public_key' => $post['ali_public_key']//支付宝公钥
            ]
        ];
        return Pay::where('code', 'alipay')->update($data);
    }

}