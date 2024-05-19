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

namespace app\shop\logic\goods;


use app\common\basics\Logic;
use app\common\model\GoodsMaterial;
use app\common\model\Material;
use app\common\model\ShopMaterial;

/**
 * 门店物料-逻辑层
 * Class MaterialLogic
 * @package app\shop\logic\goods
 */
class MaterialLogic extends Logic
{
    /**
     * @notes 门店物料列表
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/6 5:20 下午
     */
    public static function lists($get,$admin_id)
    {
        $goods_material_ids = GoodsMaterial::alias('gm')
            ->join('shop_goods sg', 'sg.goods_id = gm.goods_id')
            ->where('sg.shop_id', $admin_id)
            ->column('material_id');

        $lists = Material::field('id,code,name,material_category_id,price,sort,create_time')
            ->where('del',0)
            ->where('id','in',implode(',',$goods_material_ids))
            ->append(['material_category'])
            ->order(['sort'=>'asc','id'=>'desc'])
            ->page($get['page'], $get['limit'])
            ->select()
            ->toArray();

        $count = Material::field('id,code,name,material_category_id,price,sort,create_time')
            ->where('del',0)
            ->where('id','in',implode(',',$goods_material_ids))
            ->count();

        foreach ($lists as &$list) {
            $list['shop_price']  = ShopMaterial::where(['shop_id'=>$admin_id,'material_id'=>$list['id']])->value('price') ?? $list['price'];
        }

        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 修改门店物料
     * @param $post
     * @param $admin_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/6 5:47 下午
     */
    public static function edit($post,$admin_id)
    {
        $shop_material = ShopMaterial::where(['shop_id'=>$admin_id,'material_id'=>$post['id']])->find();
        if ($shop_material != null) {
            $shop_material->shop_id = $admin_id;
            $shop_material->material_id = $post['id'];
            $shop_material->price = $post['shop_price'];
            $shop_material->create_time = time();
        }else {
            $shop_material = new ShopMaterial;
            $shop_material->shop_id = $admin_id;
            $shop_material->material_id = $post['id'];
            $shop_material->price = $post['shop_price'];
            $shop_material->create_time = time();
        }

        return $shop_material->save();
    }

    /**
     * @notes 获取门店物料详情
     * @param $id
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/6 5:47 下午
     */
    public static function detail($id,$admin_id)
    {
        $result = Material::where('id',$id)->append(['material_category'])->find()->toArray();
        $result['shop_price']  = ShopMaterial::where(['shop_id'=>$admin_id,'material_id'=>$result['id']])->value('price') ?? $result['price'];
        return $result;
    }
}