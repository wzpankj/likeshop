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
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\shop\logic;


use app\common\basics\Logic;
use app\common\model\Admin;
use app\common\model\shop\ShopAdmin;
use app\common\model\shop\ShopRole;

class AdminLogic extends Logic
{

    /**
     * Notes: 列表
     * @param $get
     * @author 段誉(2021/4/10 11:05)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists($get, $shop_id)
    {
        $roleModel = new  ShopRole();
        $adminModel = new ShopAdmin();

        $role_column = $roleModel->getNameColumn();

        $where[] = ['del', '=', 0];
        $where[] = ['shop_id', '=', $shop_id];
        if (isset($get['role_id']) && $get['role_id']) {
            $where[] = ['role_id', '=', $get['role_id']];
        }
        if (isset($get['name']) && $get['name']) {
            $where[] = ['name', 'like', "%{$get['name']}%"];
        }
        if (isset($get['account']) && $get['account']) {
            $where[] = ['account', 'like', "%{$get['account']}%"];
        }

        $result = $adminModel->where($where)
            ->hidden(['password', 'salt'])
            ->paginate([
                'list_rows'=> $get['limit'],
                'page'=> $get['page']
            ]);

        foreach ($result as $k => $item) {
            if ($item['root'] == 1) {
                $role = '超级管理员';
            } else {
                $role = $role_column[$item['role_id']] ?? '';
            }
            $result[$k]['role'] = $role;
        }
        return ['count' => $result->total(), 'lists' => $result->getCollection()];
    }


    /**
     * Notes: 添加管理员
     * @param $post
     * @author 段誉(2021/4/10 16:14)
     * @return Admin|\think\Model
     */
    public static function addAdmin($shop_id, $post)
    {
        $time = time();
        $salt = substr(md5($time . $post['name']), 0, 4);//随机4位密码盐
        $password = generatePassword($post['password'], $salt);//生成密码
        return ShopAdmin::create([
            'name'     => $post['name'],
            'shop_id'  => $shop_id,
            'root'     => 0,
            'account'  => $post['account'],
            'password' => $password,
            'salt'     => $salt,
            'role_id'  => $post['role_id'],
            'disable'   => $post['disable']
        ]);
    }


    /**
     * Notes: 更新管理员
     * @param $post
     * @author 段誉(2021/4/10 17:11)
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function editAdmin($shop_id, $post)
    {
        $admin = ShopAdmin::where('shop_id', $shop_id)->find($post['id']);

        if(empty($admin)) {
            self::$error = '未找到相关管理员';
            return false;
        }

        $data = [
            'name'          => $post['name'],
            'account'       => $post['account'],
            'role_id'       => $post['role_id'],
            'update_time'   => time(),
            'disable'       => $post['disable']
        ];

        //生成密码
        if ($post['password']) {
            $data['password'] = generatePassword($post['password'], $admin['salt']);
        }

        //TODO 禁用管理员并强制下线
        if (1 == $post['disable'] || $admin['role_id'] != $post['role_id']) {

        }

        return $admin->save($data);
    }


    /**
     * Notes: 删除
     * @param $shop_id
     * @param $id
     * @author 段誉(2021/5/7 11:11)
     * @return ShopAdmin
     */
    public static function delAdmin($shop_id, $id)
    {
        return ShopAdmin::update([
            'account' => time() . '_' . $id,
            'del' => 1,
            'shop_id' => $shop_id
        ], ['id' => $id]);
    }


    /**
     * Notes: 修改密码
     * @param $password
     * @param $admin_id
     * @param $shop_id
     * @author 段誉(2021/5/7 11:15)
     * @return bool
     */
    public static function updatePassword($password, $admin_id, $shop_id)
    {
        try {
            $admin = ShopAdmin::where(['id' => $admin_id, 'shop_id' => $shop_id])->find();
            $admin->password = generatePassword($password, $admin['salt']);
            $admin->save();
            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

}