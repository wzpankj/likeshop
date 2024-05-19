<?php
namespace app\common\model\user;

use app\common\basics\Models;
use app\common\server\AreaServer;

class UserAddress extends Models
{
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
