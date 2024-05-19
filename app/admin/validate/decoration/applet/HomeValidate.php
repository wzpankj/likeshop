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
use app\common\model\DecorateHome;

/**
 * 首页装修验证器
 * Class HomeValidate
 * @package app\admin\validate\decoration\applet
 */
class HomeValidate extends Validate
{
    protected $rule = [
        'id' => 'require|checkId',
        'name' => 'require|checkName',
        'image' => 'require',
        'describe' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'name.require' => '菜单名称不能为空',
        'image.require' => '菜单图片不能为空',
        'describe.require' => '菜单描述不能为空',
    ];

    public function sceneEdit()
    {
        return $this->only(['id','name','image','describe']);
    }

    /**
     * @notes 校验菜单是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/9 2:27 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = DecorateHome::where(['id'=>$value])->findOrEmpty();
        if ($result->isEmpty()) {
            return '菜单不存在';
        }
        return true;
    }

    /**
     * @notes 检验菜单名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/9 2:29 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['name','=',$value];
        if (isset($data['id'])) {
            $where[] = ['id','<>',$data['id']];
        }
        $result = DecorateHome::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '菜单名称已存在';
        }
        return true;
    }
}