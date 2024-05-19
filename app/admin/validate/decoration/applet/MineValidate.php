<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\admin\validate\decoration\applet;


use app\common\basics\Validate;
use app\common\model\DecorateMine;

class MineValidate extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require|checkName',
        'image' => 'require',
        'link_type' => 'require',
        'menu' => 'requireIf:link_type,1',
        'url' => 'requireIf:link_type,2',
        'sort' => 'require|number',
        'is_show' => 'require|in:0,1',
    ];

    protected $message = [
        'id.require'        => '参数缺失',
        'name.require'     => '请输入导航名称',
        'image.require'       => '请选择导航图标',
        'link_type.require'     => '请选择链接类型',
        'menu.requireIf'     => '请选择链接菜单',
        'url.requireIf'       => '请输入自定义链接',
        'sort.require'     => '请输入排序',
        'sort.number'     => '排序只能输入纯数字',
        'is_show.require'     => '请选择状态',
    ];

    public function sceneAdd()
    {
        return $this->only(['name','image','link_type','menu','url','sort','is_show']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','image','link_type','menu','url','sort','is_show']);
    }

    public function sceneStatus()
    {
        return $this->only(['id','is_show']);
    }

    public function sceneDel()
    {
        return $this->only(['id']);
    }

    /**
     * @notes 检验菜单名称
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/9 4:23 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['name','=',$value];
        $where[] = ['del','=',0];
        if (isset($data['id'])) {
            $where[] = ['id','<>',$data['id']];
        }
        $result = DecorateMine::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '菜单名称已存在';
        }
        return true;
    }
}