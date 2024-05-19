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


namespace app\common\model;


use app\common\basics\Models;

/**
 * 角色 模型
 * Class Menu
 * @package app\common\model
 */
class Role extends Models
{
    protected $name = 'role';

    /**
     * Notes: 获取角色名称
     * @param $role_id
     * @author 段誉(2021/4/9 15:40)
     * @return mixed|string
     */
    public function getRoleName($role_id)
    {
        $role_name = $this
            ->where(['id' => $role_id])
            ->value('name');
        
        return empty($role_name) ? '系统管理员' : $role_name;
    }


    /**
     * Notes: 获取全部角色名称(以角色id为键,值为名称)
     * @param array $contidion
     * @author 段誉(2021/4/10 10:46)
     * @return array
     */
    public function getNameColumn($contidion = [])
    {
        $role_name = $this
            ->where($contidion)
            ->where('del', 0)
            ->column('name', 'id');
        return $role_name;
    }


    /**
     * Notes:
     * @param array $where
     * @param string $field
     * @author 段誉(2021/4/10 11:13)
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRoleLists($where = [], $field = "*")
    {
        $where[] = ['del', '=', 0];
        return $this->where($where)->field($field)->select();
    }

}