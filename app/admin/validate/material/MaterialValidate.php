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
use app\common\model\GoodsMaterial;
use app\common\model\Material;
use app\common\model\MaterialCategory;

/**
 * 平台物料库验证器
 * Class MaterialValidate
 * @package app\admin\validate\material
 */
class MaterialValidate extends Validate
{
    protected $rule = [
        'id' => 'require|checkId',
        'code' => 'require|checkCode|number',
        'name' => 'require|checkName|max:64',
        'material_category_id' => 'require|checkMaterialCategoryId',
        'price' => 'require',
        'sort' => 'number|max:5',
    ];

    protected $message = [
        'code.require' => '物料编号不能为空',
        'code.number' => '物料编号必须为纯数字',
        'name.require' => '物料名称不能为空',
        'material_category_id.require' => '物料分类不能为空',
        'price.require' => '价格不能为空',
        'sort.number' => '排序必须为纯数字',
        'sort.max' => '排序值最大为五位数',
    ];

    public function sceneAdd()
    {
        return $this->only(['code','name','material_category_id','price','sort']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','code','name','material_category_id','price','sort'])
            ->append('sort','require')
            ->append('id','checkEditCategory');
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }

    /**
     * @notes 检验物料编号是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 5:32 下午
     */
    public function checkCode($value,$rule,$data)
    {
        $where[] = ['code','=',$value];
        $where[] = ['del','=',0];
        if (isset($data['id'])) {
            $where[] = ['id','<>',$data['id']];
        }

        $result = Material::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '物料编号已存在';
        }
        return true;
    }

    /**
     * @notes 检验物料名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 5:33 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['code','=',$value];
        $where[] = ['del','=',0];
        if (isset($data['id'])) {
            $where[] = ['id','<>',$data['id']];
        }

        $result = Material::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '物料名称已存在';
        }
        return true;
    }

    /**
     * @notes 检验物料分类是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 5:34 下午
     */
    public function checkMaterialCategoryId($value,$rule,$data)
    {
        $result = MaterialCategory::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '物料分类不存在';
        }
        return true;
    }

    /**
     * @notes 检验物料是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 6:10 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Material::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '物料不存在';
        }
        return true;
    }

    /**
     * @notes 检验物料能否删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/2 6:38 下午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = GoodsMaterial::where('material_id',$value)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '物料正在使用中，无法删除';
        }
        return true;
    }

    /**
     * @notes 检验物料是否可以修改分类
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/6 5:59 下午
     */
    public function checkEditCategory($value,$rule,$data)
    {
        $result = GoodsMaterial::where('material_id',$value)->findOrEmpty();
        $material_category_id = Material::where('id',$value)->value('material_category_id');
        if (!$result->isEmpty() && $material_category_id != $data['material_category_id']) {
            return '物料正在使用中，不可以切换分类';
        }
        return true;
    }
}