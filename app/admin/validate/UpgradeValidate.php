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

namespace app\admin\validate;

use app\admin\logic\system\UpgradeLogic;
use app\common\basics\Validate;


class UpgradeValidate extends Validate
{

    protected $rule = [
        'version_no' => 'require|checkIsAbleUpgrade',
        'package_link' => 'require'
    ];


    protected $message = [
        'version_no.require' => '参数缺失',
        'package_link.require' => '参数缺失',
    ];


    //检查是否可以更新
    protected function checkIsAbleUpgrade($value, $reule, $data)
    {
        $version_data = local_version();
        $local_version = $version_data['version'];

        //本地版本需要小于当前选中版本
        if ($local_version > $value) {
            return '当前系统无法升级到该版本，请重新选择更新版本。';
        }

        //获取远程列表
        $remote_data = UpgradeLogic::getRemoteVersion();
        if (empty($remote_data)) {
            return '获取更新数据失败';
        }

        foreach ($remote_data as $k => $item) {
            if ($item['version_no'] != $local_version) {
                continue;
            }

            if (empty($remote_data[$k - 1])) {
                return '已为最新版本';
            }

            if ($remote_data[$k - 1]['version_no'] != $value) {
                return '当前系统无法升级到该版本，请重新选择更新版本。';
            }
        }
        return true;
    }

}