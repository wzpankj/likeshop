<?php
namespace app\admin\logic\user;

use app\common\{
    basics\Logic,
    enum\OrderEnum,
    model\user\User,
    enum\ClientEnum,
    server\UrlServer,
    model\AccountLog,
    model\order\Order,
    logic\AccountLogLogic

};
use think\Exception;
use think\facade\Db;

/**
 * 用户逻辑层
 * Class UserLogic
 * @package app\admin\logic\user
 */
class UserLogic extends Logic
{
    /**
     * @notes 用户列表
     * @param $get
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/22 14:49
     */
    public static function lists($get)
    {
        $where[] = ['del','=',0];
        //查询
        if(isset($get['keyword']) && $get['keyword']){
            $where[] = ['nickname|sn','like', '%'.$get['keyword'].'%'];
        }
        //注册时间
        if(isset($get['start_time']) && $get['start_time']!=''){
            $where[] = ['create_time','>=',strtotime($get['start_time'])];
        }
        if(isset($get['end_time']) && $get['end_time']!=''){
            $where[] = ['create_time','<=',strtotime($get['end_time'])];
        }

        $user_count = User::where($where)->count();

        $user_list = User::where($where)
            ->field('id,sn,nickname,avatar,total_order_amount,client,login_time,create_time,user_money')
            ->page($get['page'],$get['limit'])
            ->order('id desc')
            ->select()
            ->toArray();

        foreach ($user_list as &$item){
            // 头像
            if ($item['avatar'] != '/static/common/image/default/user.png') {
                $item['abs_avatar'] = $item['avatar'] ? UrlServer::getFileUrl($item['avatar']) : '';
            } else {
                $item['abs_avatar'] = '/static/common/image/default/user.png';
            }
        }

        return ['count'=>$user_count , 'lists'=>$user_list];
    }

    /**
     * @notes 用户信息
     * @param $id
     * @return array
     * @author cjhao
     * @date 2021/9/22 16:46
     */
    public static function getUser($id)
    {
        $user = User::field('id,sn,nickname,avatar,birthday,sex,mobile,client,remark,create_time,login_time,user_money,total_order_amount,total_recharge_amount')
            ->where([
                'del' => 0,
                'id' => $id
            ])->findOrEmpty();
        if($user->isEmpty()) {
            return [];
        }
        // 头像
        if($user['avatar']) {
            $user['avatar'] = UrlServer::getFileUrl($user['avatar']);
        }

        return $user->toArray();
    }

    /**
     * @notes 编辑用户
     * @param $post
     * @return bool
     * @author cjhao
     * @date 2021/9/22 16:30
     */
    public static function edit($post)
    {

        $data = [
            'id'            => $post['id'],
            'nickname'      => $post['nickname'],
            'avatar'        => clearDomain($post['avatar']),
            'mobile'        => $post['mobile'],
            'birthday'      => strtotime($post['birthday']),
            'remark'        => $post['remark'],
            'update_time'   => time()
        ];
        User::update($data);

    }

    /**
     * @notes 用户资料
     * @param $id
     * @return array|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/22 16:47
     */
    public static function getInfo($id)
    {
        $user = User::field('id,sn,nickname,avatar,birthday,sex,mobile,client,create_time,login_time,user_money,remark,total_order_amount,total_recharge_amount')
            ->append(['sex_desc','client_desc'])
            ->findOrEmpty($id);
        if($user->isEmpty()) {
            return [];
        }

        $user = $user->toArray();

        //到店订单
        $user['store_order'] = Order::where(['order_status'=>OrderEnum::ORDER_STATUS_COMPLETE,'order_type'=>OrderEnum::ORDER_TYPE_YOUSELF_TAKE,'user_id'=>$id])->count();
        //外卖订单
        $user['take_order'] = Order::where(['order_status'=>OrderEnum::ORDER_STATUS_COMPLETE,'order_type'=>OrderEnum::ORDER_TYPE_TAKE_AWAY,'user_id'=>$id])->count();
        //成交订单
        $user['order_num'] =  Order::where(['order_status'=>OrderEnum::ORDER_STATUS_COMPLETE,'user_id'=>$id])->count();
        //消费金额
        $user['order_amount'] = Order::where(['order_status'=>OrderEnum::ORDER_STATUS_COMPLETE,'user_id'=>$id])->sum('order_amount');
        //客单价
        $user['avg_amount'] = $user['order_num'] ? round($user['order_amount'] / $user['order_num'],2) : '0.00';
        // 头像
        $user['avatar'] = UrlServer::getFileUrl($user['avatar']);

        return $user;
    }

    /**
     * @notes 调整账户余额
     * @param $post
     * @return bool
     * @author cjhao
     * @date 2021/9/22 16:46
     */
    public static function adjustAccount($post)
    {
        Db::startTrans();
        try{

            $user = User::findOrEmpty($post['id']);
            if($user->isEmpty()) {
                throw new Exception('用户不存在');
            }
            // 余额调整
            if($post['money_handle'] == 1) { // 增加

                $user->user_money = $user->user_money + $post['money'];
                $user->save();
                AccountLogLogic::AccountRecord($user->id, $post['money'],1,AccountLog::admin_add_money);

            }else{
                if($user->user_money < $post['money']){
                    throw new Exception('当前用户仅剩:'.$user->user_money);
                }

                $user->user_money = $user->user_money - $post['money'];
                $user->save();
                AccountLogLogic::AccountRecord($user->id, $post['money'],0,AccountLog::admin_reduce_money);
            }

            Db::commit();
            return true;
        }catch(Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }
}
