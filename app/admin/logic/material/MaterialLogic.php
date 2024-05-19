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

namespace app\admin\logic\material;


use app\common\basics\Logic;
use app\common\enum\DefaultConfigEnum;
use app\common\model\Material;
use app\common\model\MaterialCategory;

/**
 * 平台物料库-逻辑层
 * Class MaterialLogic
 * @package app\admin\logic\material
 */
class MaterialLogic extends Logic
{
    /**
     * @notes 物料列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 5:22 下午
     */
    public static function lists($get)
    {
        $lists = Material::field('id,code,name,material_category_id,price,sort,create_time')
            ->where('del',0)
            ->append(['material_category'])
            ->order(['sort'=>'asc','id'=>'desc'])
            ->page($get['page'], $get['limit'])
            ->select()
            ->toArray();

        $count = Material::where('del',0)->count();

        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 添加物料
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/9/2 5:42 下午
     */
    public static function add($post)
    {
        $material_category = new Material;
        $material_category->code = $post['code'];
        $material_category->name = $post['name'];
        $material_category->material_category_id = $post['material_category_id'];
        $material_category->price = $post['price'];
        $material_category->sort = (isset($post['sort']) && !empty($post['sort'])) ? $post['sort'] : DefaultConfigEnum::SORT;
        $material_category->create_time = time();
        return $material_category->save();
    }

    /**
     * @notes 获取物料分类列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 6:07 下午
     */
    public static function getCategoryLists()
    {
        return MaterialCategory::where('del',0)->select()->toArray();
    }

    /**
     * @notes 编辑物料
     * @param $post
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 6:34 下午
     */
    public static function edit($post)
    {
        $material_category = Material::find($post['id']);
        $material_category->code = $post['code'];
        $material_category->name = $post['name'];
        $material_category->material_category_id = $post['material_category_id'];
        $material_category->price = $post['price'];
        $material_category->sort = $post['sort'];
        $material_category->update_time = time();
        return $material_category->save();
    }

    /**
     * @notes 物料详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 6:34 下午
     */
    public static function detail($id)
    {
        return Material::where('id',$id)->append(['material_category'])->find()->toArray();
    }

    /**
     * @notes 删除物料
     * @param $post
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/2 6:39 下午
     */
    public static function del($post)
    {
        $material = Material::find($post['id']);
        $material->del = 1;
        $material->update_time = time();
        return $material->save();
    }
}