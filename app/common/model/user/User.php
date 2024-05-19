<?php
namespace  app\common\model\user;

use app\common\basics\Models;
use app\common\model\user\UserLevel;
use app\common\model\distribution\DistributionOrderGoods;
use app\common\server\UrlServer;
use app\common\enum\ClientEnum;

/**
 * 用户模型
 * Class User
 * @package app\common\model\user
 */
class User extends Models
{
    public function getSexDescAttr($value,$data)
    {
        switch ($data['sex']){
            case 1:
                return '男';
            case 2:
                return '女';
            default:
                return '未知';
        }
    }

    public function getBirthdayAttr($value)
    {
        return $value ? date('Y-m-d', $value) : $value;
    }

    public function getLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getAvatarAttr($value)
    {
        return empty($value) ? '' : UrlServer::getFileUrl($value);
    }

    public function getClientDescAttr($value,$data)
    {
        return ClientEnum::getClient($data['client']);
    }
}