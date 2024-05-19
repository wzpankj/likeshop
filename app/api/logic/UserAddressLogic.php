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


namespace app\api\logic;


use app\common\{model\DevRegion, server\AreaServer, model\user\UserAddress, server\ConfigServer};
use think\facade\Db;
use think\Exception;

/**
 * 用户地址逻辑层
 * Class UserAddressLogic
 * @package app\api\logic
 */
class UserAddressLogic
{

    /**
     * @notes 用户列表
     * @param int $user_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/7 16:27
     */
    public static function lists(int $user_id)
    {
        $UserAddress = new UserAddress();
        $info = $UserAddress
            ->where(['user_id' => $user_id, 'del' => 0])
            ->field('id,contact,telephone,province_id,city_id,district_id,location,address,is_default,longitude,latitude')
            ->select();
        foreach ($info as $item) {
            $item['province'] = AreaServer::getAddress($item['province_id']);
            $item['city'] = AreaServer::getAddress($item['city_id']);
            $item['district'] = AreaServer::getAddress($item['district_id']);
        }
        return $info->toArray();
    }

    /**
     * 获取一条地址信息
     * @param $user_id
     * @param $get
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAddress($user_id, $get)
    {
        $UserAddress = new UserAddress();
        $info = $UserAddress
            ->where(['id' => (int)$get['id'], 'user_id' => $user_id, 'del' => 0])
            ->field('id,contact,telephone,province_id,city_id,district_id,location,address,is_default,longitude,latitude')
            ->find()
            ->toArray();

        $info['province'] = AreaServer::getAddress($info['province_id']);
        $info['city'] = AreaServer::getAddress($info['city_id']);
        $info['district'] = AreaServer::getAddress($info['district_id']);
        return $info;
    }

    /**
     * 获取默认地址
     * @param $user_id
     * @return array
     */
    public static function getDefaultAddress($user_id)
    {
        $UserAddress = new UserAddress();
        $info = $UserAddress
            ->where(['is_default' => 1, 'user_id' => $user_id, 'del' => 0])
            ->field('id,contact,telephone,province_id,city_id,district_id,location,address,is_default')
            ->findOrEmpty()->toArray();

        if (!$info) {
            return [];
        }

        $info['province'] = AreaServer::getAddress($info['province_id']);
        $info['city'] = AreaServer::getAddress($info['city_id']);
        $info['district'] = AreaServer::getAddress($info['district_id']);

        return $info;
    }

    /**
     * 设置默认地址
     * @param $user_id
     * @param $post
     * @return int|string
     */
    public static function setDefaultAddress($user_id, $post)
    {
        try {
            Db::startTrans();
            $UserAddress = new UserAddress();
            $UserAddress->where(['del' => 0, 'user_id' => $user_id])->update(['is_default' => 0]);

            $UserAddress->where(['id' => $post['id'], 'del' => 0, 'user_id' => $user_id])->update(['is_default' => 1]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    /**
     * 添加收货地址
     * @param $user_id
     * @param $post
     * @return int|string
     */
    public static function addUserAddress($user_id, $post)
    {
        try {
            Db::startTrans();
            //获取省市区id
            $map_key = ConfigServer::get('map','qq_map_key','');
            $address_info = json_decode(file_get_contents('https://apis.map.qq.com/ws/geocoder/v1/?location='.$post['latitude'].','.$post['longitude'].'&key='.$map_key),true);
            if(isset($address_info['status']) && 0 != $address_info['status']){
                throw new Exception('位置获取失败:'.$address_info['message']);
            }
            $address_ids = UserAddressLogic::handleRegion($address_info['result']['address_component']['province'], $address_info['result']['address_component']['city'], $address_info['result']['address_component']['district']);

            //更新用户地址默认状态
            $UserAddress = new UserAddress();
            if ($post['is_default'] == 1) {
                $UserAddress
                    ->where(['del' => 0, 'user_id' => $user_id])
                    ->update(['is_default' => 0]);
            } else {
                $is_first = $UserAddress
                    ->where(['del' => 0, 'user_id' => $user_id])
                    ->select()->toArray();
                if (empty($is_first)) {
                    $post['is_default'] = 1;
                }
            }

            $data = [
                'user_id'       => $user_id,
                'contact'       => $post['contact'],
                'telephone'     => $post['telephone'],
                'province_id'   => $address_ids['province'],
                'city_id'       => $address_ids['city'],
                'district_id'   => $address_ids['district'],
                'location'      => $address_info['result']['address'],
                'address'       => $post['address'],
                'is_default'    => $post['is_default'],
                'longitude'     => $post['longitude'],
                'latitude'      => $post['latitude'],
                'create_time'   => time()
            ];

            $UserAddress->insert($data);

            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * 编辑用户地址
     * @param $user_id
     * @param $post
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function editUserAddress($user_id, $post)
    {
        try {
            Db::startTrans();
            //获取省市区id
            $map_key = ConfigServer::get('map','qq_map_key','');
            $address_info = json_decode(file_get_contents('https://apis.map.qq.com/ws/geocoder/v1/?location='.$post['latitude'].','.$post['longitude'].'&key='.$map_key),true);
            if(isset($address_info['status']) && 0 != $address_info['status']){
                throw new Exception('位置获取失败:'.$address_info['message']);
            }
            $address_ids = UserAddressLogic::handleRegion($address_info['result']['address_component']['province'], $address_info['result']['address_component']['city'], $address_info['result']['address_component']['district']);

            //更新用户地址默认状态
            $UserAddress = new UserAddress();
            if ($post['is_default'] == 1) {
                $UserAddress->where(['del' => 0, 'user_id' => $user_id])
                    ->update(['is_default' => 0]);
            }

            $data = [
                'contact'       => $post['contact'],
                'telephone'     => $post['telephone'],
                'province_id'   => $address_ids['province'],
                'city_id'       => $address_ids['city'],
                'district_id'   => $address_ids['district'],
                'location'      => $address_info['result']['address'],
                'address'       => $post['address'],
                'is_default'    => $post['is_default'],
                'longitude'     => $post['longitude'],
                'latitude'      => $post['latitude'],
                'update_time'   => time()
            ];

            $UserAddress->where(['id' => $post['id'], 'del' => 0, 'user_id' => $user_id])->update($data);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    /**
     * 删除用户地址
     * @param $user_id
     * @param $post
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function delUserAddress($user_id, $post)
    {
        $data = [
            'del' => 1,
            'update_time' => time()
        ];
        $UserAddress = new UserAddress();
        $UserAddress->where(['id' => $post['id'], 'del' => 0, 'user_id' => $user_id])->update($data);
        return true;
    }


    /**
     * 获取省市区id
     * @param $province
     * @param $city
     * @param $district
     * @return array
     */
    public static function handleRegion($province, $city, $district)
    {
        if (!$province || !$city || !$district) {
            return [];
        }
        $result = [];
        $result['province'] = self::handleRegionField($province, 1);
        if (!$result['province']) {
            return [];
        }
        $result['city'] = self::handleRegionField($city, 2, $result['province']);
        $result['district'] = self::handleRegionField($district, 3, $result['city']);

        return $result;
    }

    /**
     * 获取对应省,市,区的id
     * @param $keyword
     * @param int $level
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function handleRegionField($keyword, $level = 1, $parent_id = '')
    {
        $data = '';
        $where[] = ['level', '=', $level];
        if (!empty($parent_id)) {
            $where[] = ['parent_id', '=', $parent_id];
        }

        $DevRegion = new DevRegion();
        $lists = $DevRegion->where($where)->select();
        foreach ($lists as $k => $v) {
            if ($keyword == $v['name'] || strpos($keyword, $v['name']) !== false) {
                $data = $v['id'];
            }
        }
        return $data;
    }


    /**
     * User: 意象信息科技 mjf
     * Desc: 获取用户指定id的地址
     * @param $address
     * @param $user_id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserAddressById($address, $user_id)
    {
        $UserAddress = new UserAddress();
        $info = $UserAddress
            ->where(['id' => $address, 'user_id' => $user_id, 'del' => 0])
            ->field('id,contact,telephone,province_id,city_id,district_id,address,is_default')
            ->find();

        if (!$info) {
            return [];
        }

        $info['province'] = AreaServer::getAddress($info['province_id']);
        $info['city'] = AreaServer::getAddress($info['city_id']);
        $info['district'] = AreaServer::getAddress($info['district_id']);

        return $info;
    }


    /**
     * User: 意象信息科技 mjf
     * Desc: 获取下单时用户地址
     * @param $data
     * @param $user_id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOrderUserAddress($data, $user_id)
    {
        if (isset($data['address_id']) && $data['address_id'] != 0) {
            return self::getUserAddressById($data['address_id'], $user_id);
        }
        return self::getDefaultAddress($user_id);
    }
}