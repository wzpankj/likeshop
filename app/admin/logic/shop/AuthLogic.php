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


namespace app\admin\logic\shop;


use app\common\basics\Logic;
use app\common\model\shop\ShopAuth;
use app\common\server\ArrayServer;

/**
 * 商家菜单逻辑
 * Class AuthLogic
 * @package app\admin\logic\shop
 */
class AuthLogic extends Logic
{

    /**
     * Notes: 列表
     * @author 段誉(2021/4/12 16:38)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists()
    {
        $authModel = new ShopAuth();
        $lists = $authModel
            ->where(['del' => 0])
            ->field(['id', 'type', 'system', 'pid', 'name', 'sort', 'icon', 'uri', 'disable'])
            ->order(['sort' => 'desc', 'type' => 'asc'])
            ->select()->toArray();
        $pids = $authModel
            ->where(['del'=>0,'type'=>1])
            ->column('pid');

        foreach ($lists as $k => $v) {
            $lists[$k]['type_str'] = $v['type'] == 1 ? '菜单' : '权限';
            $lists[$k]['open'] = in_array($v['id'],$pids)  ? true : false;
        }

        return ArrayServer::linear_to_tree($lists);
    }


    /**
     * Notes: 添加
     * @param $post
     * @author 段誉(2021/4/12 16:38)
     * @return int|string
     */
    public static function addMenu($post)
    {
        $level = self::getParent($post['pid']);
        if ($level > 3) {
            self::$error = '菜单不允许超出三级';
            return false;
        }

        $data = [
            'pid' => $post['pid'],
            'type' => $post['type'],
            'name' => $post['name'],
            'icon' => $post['icon'],
            'sort' => $post['sort'],
            'uri' => $post['uri'],
            'disable' => $post['disable'],
        ];
        return (new ShopAuth())->insert($data);
    }


    /**
     * Notes: 修改
     * @param $post
     * @author 段誉(2021/4/12 16:38)
     * @return Auth|string
     */
    public static function editMenu($post)
    {
        $level = self::getParent($post['pid']);
        if ($level > 3) {
            self::$error = '菜单不允许超出三级';
            return false;
        }

        $data = [
            'pid' => $post['pid'],
            'type' => $post['type'],
            'name' => $post['name'],
            'icon' => $post['icon'],
            'sort' => $post['sort'],
            'uri' => $post['uri'],
            'disable' => $post['disable'],
        ];
        return ShopAuth::where(['id' => $post['id'], 'system' => 0])->update($data);
    }



    /**
     * Notes: 菜单选项
     * @param string $id
     * @author 段誉(2021/4/12 16:38)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function chooseMenu($id = '')
    {
        $authModel = new ShopAuth();
        $lists = $authModel
            ->field(['id', 'pid', 'name'])
            ->where(['del' => 0, 'type' => 1])
            ->select();
        if ($id) {
            $remove_ids = self::getChildIds($lists, $id);
            $remove_ids[] = $id;
            foreach ($lists as $key => $row) {
                if (in_array($row['id'], $remove_ids)) {
                    unset($lists[$key]);
                }
            }
            $lists = array_values($lists);
        }
        return ArrayServer::multilevel_linear_sort($lists, '|-');
    }



    /**
     * Notes: 查找层级
     * @param $pid
     * @author 段誉(2021/4/12 16:38)
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getParent($pid)
    {
        static $count = 0;
        $auth = (new ShopAuth())->where(['id' => $pid, 'type' => 1])->find();
        if ($auth) {
            $count += 1;
            if ($count < 3) {
                self::getParent($auth['pid']);
            }
            return $count;
        }
        return $count;
    }


    /**
     * Notes: 子级id
     * @param $lists
     * @param $id
     * @author 段誉(2021/4/12 16:39)
     * @return array
     */
    public static function getChildIds($lists, $id)
    {
        $ids = [];
        foreach ($lists as $key => $row) {
            if ($row['pid'] == $id) {
                $ids[] = $row['id'];
                $child_ids = self::getChildIds($lists, $row['id']);
                foreach ($child_ids as $child_id) {
                    $ids[] = $child_id;
                }
            }
        }
        return $ids;
    }


    /**
     * Notes: 详情
     * @param $id
     * @author 段誉(2021/4/12 16:39)
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id)
    {
        return ShopAuth::where(['del' => 0, 'id' => $id])
            ->field(['id', 'pid', 'type', 'name', 'sort', 'icon', 'uri', 'disable'])
            ->find();
    }


    /**
     * Notes: 删除
     * @param $ids
     * @author 段誉(2021/4/12 16:39)
     * @return Auth
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delMenu($ids)
    {
        $lists = ShopAuth::where(['del' => 0])
            ->field(['id', 'pid', 'name', 'sort', 'icon', 'disable'])
            ->select();

        $del_ids = $ids;
        foreach ($ids as $id) {
            $temp = self::getChildIds($lists, $id);
            $del_ids = array_merge($del_ids, $temp);
        }

        ShopAuth::where('id', 'in', $del_ids)
            ->where(['del' => 0, 'system' => 0])
            ->update(['del' => 1]);
    }


    /**
     * Notes: 设置状态
     * @param $post
     * @author 段誉(2021/4/12 16:39)
     * @return Auth
     */
    public static function setStatus($post)
    {
        $data = [
            'disable' => $post['disable'],
            'update_time' => time(),
        ];
        return ShopAuth::where(['id' => $post['id'], 'system' => 0])
            ->update($data);
    }

}