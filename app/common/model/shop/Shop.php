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

namespace app\common\model\shop;


use app\common\basics\Models;
use app\common\server\AreaServer;

/**
 * 门店模型
 * Class Shop
 * @package app\common\model\shop
 */
class Shop extends Models
{

    public function shopRootAdmin()
    {
        return $this->hasOne(ShopAdmin::class,'shop_id');
    }

    /**
     * @notes 获取省名称
     * @param $value
     * @param $data
     * @return mixed|string
     * @author cjhao
     * @date 2021/9/9 14:42
     */
    public function getProvinceNameAttr($value,$data){
        return  AreaServer::getAddress($data['province_id']);
    }

    /**
     * @notes 获取城市名称
     * @param $value
     * @param $data
     * @return mixed|string
     * @author cjhao
     * @date 2021/9/9 14:42
     */
    public function getCityNameAttr($value,$data){
        return  AreaServer::getAddress($data['city_id']);

    }

    /**
     * @notes 获取区名称
     * @param $value
     * @param $data
     * @return mixed|string
     * @author cjhao
     * @date 2021/9/9 14:43
     */
    public function getDistrictNameAttr($value,$data){
        return  AreaServer::getAddress($data['district_id']);
    }

}