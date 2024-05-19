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


namespace app\common\server;

use app\common\model\dev\DevRegion;

class AreaServer
{
    /**
     * 通过id获取地址
     * @param $val (为非数组，返回单独地点名，为数组时，按顺序拼接地址返回)
     * @param string $address val为数组时，连接详细地址一起返回
     * @return mixed|string
     */
    public static function getAddress($val, $address = '')
    {
        $region = cache('region');
        if(!$region) { // 无缓存
            $region = DevRegion::column('id,name', 'id');
            cache('region', $region, 3600);
        }
        // 有缓存
        if(is_array($val)) { // 数组
            $temp = '';
            foreach($val as $v) {
                $temp .= $region[$v] ? $region[$v]['name'] : '';
            }
            return $temp.$address;
        }
        // 非数组
        return $region[$val] ? $region[$val]['name'] : '';
    }

    /**
     * 通过id获取地址经纬度上
     * @param $id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getDb09LngAndLat($id)
    {
        return Db::name('dev_region')
            ->where('id', '=', $id)
            ->field(['db09_lng', 'db09_lat'])
            ->find();
    }
}