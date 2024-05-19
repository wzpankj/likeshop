<?php
namespace app\api\logic;

use app\api\controller\Account;
use app\common\basics\Logic;
use app\common\model\goods\GoodsCollect;
use app\common\model\user\User;
use app\common\model\order\Order;
use app\common\model\AfterSale;
use app\common\model\CouponList;
use app\common\model\user\UserLevel;
use app\common\server\ConfigServer;
use app\common\model\AccountLog;
use app\common\server\UrlServer;
use app\common\server\WeChatServer;
use app\common\server\storage\Driver as StorageDriver;
use think\facade\Db;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\Exception;

/**
 * 用户逻辑层
 * Class UserLogic
 * @package app\api\logic
 */
class UserLogic extends Logic
{
    /***
     * 个人中心
     */
    public static function center($user_id)
    {
        $user = User::findOrEmpty($user_id);
        if($user->isEmpty()) {
            return [];
        }
        // 头像
        $user->avatar = UrlServer::getFileUrl($user->avatar);

        $user->visible(['id','nickname','sn','avatar', 'mobile','user_money','sex','birthday','integral']);
        $user = $user->toArray();
        return $user;
    }

    /***
     * 获取用户信息
     */
    public static function getUserInfo($user_id)
    {
        $info = User::where(['id' => $user_id])
            ->field('id,sn,nickname,avatar,mobile,sex,birthday,create_time')
            ->find()
            ->toArray();

        return $info;
    }

    /**
     * 设置个人信息
     */
    public static function setUserInfo($post,$user_id)
    {
        $user = User::find($user_id);
        $user->avatar = $post['avatar'];
        $user->nickname = $post['nickname'];
        $user->mobile = $post['mobile'] ?? '';
        $user->sex = $post['sex'];
        $user->birthday = $post['birthday'] ?? '';
        return $user->save();
    }

    /**
     * 获取微信手机号
     */
    public static function getMobileByMnp($post)
    {
        try {
            $config = WeChatServer::getMnpConfig();
            $app = Factory::miniProgram($config);
            $response = $app->auth->session($post['code']);
            if (!isset($response['session_key'])) {
                throw new Exception();
            }
            $response = $app->encryptor->decryptData($response['session_key'], $post['iv'], $post['encrypted_data']);

            return $response;
        } catch (Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * 修改手机号
     */
    public static function changeMobile($user_id, $data)
    {
        $user = User::find($user_id);
        $user->mobile = $data['new_mobile'];
        $user->save();
        return ['mobile'=>$user->mobile];
    }

    /**
     * 余额明细
     */
    public static function balanceDetails($user_id,$source,$page,$size)
    {
        $where[] = ['user_id','=',$user_id];
        switch ($source){
            case 0:
                $where[] = ['source_type','in',AccountLog::money_change];
                break;
            case 1:
                $where[] = ['source_type','=',AccountLog::balance_pay_order];
                break;
            case 2:
                $where[] = ['source_type','=',AccountLog::recharge_money];
            default:
                break;

        }

        $count = AccountLog::where($where)->count();

        $list = AccountLog::where($where)
            ->page($page,$size)
            ->order('id desc')
            ->field('id,change_amount,change_amount as change_amount_format,source_type,change_type,create_time')
            ->select()
            ->toArray();

        $more = is_more($count,$page,$size);  //是否有下一页

        $data = [
            'list'          => $list,
            'page_no'       => $page,
            'page_size'     => $size,
            'count'         => $count,
            'more'          => $more
        ];
        return $data;
    }


}
