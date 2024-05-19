<?php
namespace  app\admin\validate\goods;

use app\common\model\goods\Goods;
use app\common\model\goods\GoodsCategory;
use app\common\basics\Validate;
use app\common\model\MaterialCategory;

/**
 * 平台商品验证器
 * Class GoodsValidate
 * @package app\admin\validate\goods
 */
class GoodsValidate extends Validate
{
    protected $rule = [
        'name' => 'require|checkGoodsName',
        'code' => 'checkCode',
        'goods_category_id' => 'require|checkGoodsCategoryId',
        'image' => 'require',
        'status' => 'require|in:0,1',
        'sort' => 'integer|egt:0',
        'goods_ids' => 'require|array',
        'goods_id' => 'require',
        'material_category' => 'array|checkMaterialCategory',
    ];

    protected  $message= [
        'name.require' => '商品名称不能为空',
        'goods_category_id.require' => '商品分类不能为空',
        'image.require' => '商品封面图不能为空',
        'status.require' => '销售状态不能为空',
        'sort.integer' => '排序权重须为整型',
        'sort.egt' => '排序权重须大于或等于0',
    ];

    public function sceneAdd()
    {
        return $this->only(['name','code','goods_category_id','image','status','sort','material_category']);
    }

    public function sceneStatus()
    {
        return $this->only(['goods_ids','status']);
    }

    public function sceneEdit()
    {
        return $this->only(['goods_id','name','code','goods_category_id','image','status','sort','material_category']);
    }

    /**
     * @notes 检验商品名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 11:41 上午
     */
    public function checkGoodsName($value,$rule,$data)
    {
        $where[] = ['name', '=', $value];
        $where[] = ['del', '=', 0];
        // 编辑的情况，要排除自身ID
        if (isset($data['goods_id'])) {
            $where[] = ['id', '<>', $data['goods_id']];
        }
        $result = Goods::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '商品名称已存在';
        }
        return true;
    }

    /**
     * @notes 检验商品分类是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/8/30 11:43 上午
     */
    public function checkGoodsCategoryId($value,$rule,$data)
    {
        $result = GoodsCategory::where(['del'=>0,'id'=>$value])->findOrEmpty();
        if ($result->isEmpty()) {
            return '商品分类不存在';
        }
        return true;
    }

    /**
     * @notes 检查商品编码是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/8/30 2:23 下午
     */
    public function checkCode($value,$rule,$data)
    {
        if ($value) {
            $where[] = ['code', '=', $value];
            $where[] = ['del', '=', 0];
            // 编辑的情况，要排除自身ID
            if (isset($data['goods_id'])) {
                $where[] = ['id', '<>', $data['goods_id']];
            }
            $result = Goods::where($where)->findOrEmpty();
            if(!$result->isEmpty()) {
                return '商品编码已存在';
            }
        }
        return true;
    }

    /**
     * @notes 检验物料信息
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/7 10:47 上午
     */
    public function checkMaterialCategory($value,$rule,$data)
    {
        if ($value) {
            $result = array_column($value,'material_category_id');
            if (count($result) != count(array_unique($result))) {
                return '存在相同物料分类，请重新提交';
            }
            foreach ($value as $val) {
                $material_category = MaterialCategory::where('id',$val['material_category_id'])->findOrEmpty();
                if ($material_category->isEmpty()) {
                    return '参数错误';
                }
                if (!isset($val['material']) && empty($val['material'])) {
                    return $material_category['name'].'下的物料不能为空';
                }
            }
        }
        return true;
    }
}