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

namespace app\admin\validate\material;


use app\common\basics\Validate;
use app\common\model\GoodsMaterialCategory;
use app\common\model\MaterialCategory;

/**
 * 平台物料分类验证器
 * Class CategoryValidate
 * @package app\admin\validate\material
 */
class CategoryValidate extends Validate
{
    protected $rule = [
        'id' => 'require|checkId',
        'name' => 'require|checkName',
    ];

    protected $message = [
        'name.require' => '分类名称不能为空'
    ];

    public function sceneAdd()
    {
        return $this->only(['name']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }

    /**
     * @notes 检验物料分类名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 3:45 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['name','=',$value];
        $where[] = ['del','=',0];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = MaterialCategory::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '分类名称已存在';
        }
        return true;
    }

    /**
     * @notes 检验分类是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 4:00 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = MaterialCategory::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '分类不存在';
        }
        return true;
    }

    /**
     * @notes 检验物料分类能否删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 5:07 下午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = GoodsMaterialCategory::where('category_id',$value)->select()->toArray();
        if (!empty($result)) {
            return '物料分类正在使用中，无法删除';
        }
        return true;
    }
}