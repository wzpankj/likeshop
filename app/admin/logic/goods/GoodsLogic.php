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


namespace app\admin\logic\goods;


use app\common\basics\Logic;
use app\common\enum\DefaultConfigEnum;
use app\common\enum\DefaultEnum;
use app\common\enum\GoodsEnum;
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsCategory;
use app\common\model\goods\GoodsColumn;
use app\common\model\goods\GoodsImage;
use app\common\model\goods\GoodsItem;
use app\common\model\goods\GoodsSpec;
use app\common\model\goods\GoodsSpecValue;
use app\common\model\goods\Supplier;
use app\common\model\GoodsMaterial;
use app\common\model\GoodsMaterialCategory;
use app\common\model\MaterialCategory;
use app\common\model\shop\ShopGoods;
use app\common\model\shop\ShopGoodsItem;
use app\common\server\UrlServer;
use think\facade\Db;
use app\common\model\seckill\SeckillGoods;


/**
 * 平台商品管理-逻辑层
 * Class GoodsLogic
 * @package app\shop\logic\goods
 */
class GoodsLogic extends Logic
{
    /*
     * 商品统计
     */
    public static function statistics(){
        $where = [
            ['g.del', '=', GoodsEnum::DEL_NORMAL]
        ];
        $goods_list = Goods::alias('g')
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->where($where)
            ->field('g.status')
            ->select();

        $goods = [
            'all'      => 0,       //全部
            'upper' => 0,       //总部可售
            'lower'   => 0,       //总部停售
        ];
        foreach ($goods_list as $item){
            // 全部
            $goods['all']++;

            // 总部可售
            if($item['status'] == GoodsEnum::STATUS_SHELVES) {
                $goods['upper']++;
            }

            // 总部停售
            if($item['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                $goods['lower']++;
            }
        }

        return $goods;
    }

    /**
     * Notes: 列表
     * @param $get
     * @author 段誉(2021/4/15 10:53)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function lists($get)
    {
        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.goods_category_id','=', $get['goods_category']];
        }

        $type = $get['type'] ?: 0;

        switch ($type) {
            case 2:      //总部可售
                $where[] = ['g.status', '=', GoodsEnum::STATUS_SHELVES];
                break;
            case 3:     //总部停售
                $where[] = ['g.status', '=', GoodsEnum::STATUS_SOLD_OUT];
                break;
            default:
                break;
        }

        $lists = Goods::alias('g')
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->field('g.id, g.image, g.name, gc.name as goods_category_name, g.min_price, g.max_price, g.status, g.sort, g.create_time')
            ->where($where)
            ->page($get['page'], $get['limit'])
            ->order(['g.sort'=>'asc','g.id'=>'desc'])
            ->select();

        $count = Goods::alias('g')->Join('goods_category gc', 'gc.id=g.goods_category_id')->where($where)->count();

        foreach($lists as &$item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部可售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }

            $item['sales_actual'] = ShopGoods::where('goods_id',$item['id'])->sum('sales_sum');
        }
        if($count) {
            $lists = $lists->toArray();
        }else{
            $lists = [];
        }
        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 添加商品
     * @param $post
     * @param $spec_lists
     * @return bool
     * @author ljj
     * @date 2021/8/30 9:51 上午
     */
    public static function add($post, $spec_lists)
    {
        Db::startTrans();
        try {
            // 图片去除域名
//            $post['image'] = str_replace(request()->domain(), '', $post['image']);

            //添加商品主表
            $goods_id = self::addGoods($post);

            //添加规格项、规格值、SKU
            if ($post['spec_type'] == 1) {
                self::addOneSpec($goods_id, $post);
            } else {
                self::addMoreSpec($goods_id, $post, $spec_lists);
            }

            //添加商品物料
            if (isset($post['material_category'])) {
                $material_data = [];
                foreach ($post['material_category'] as $category) {
                    $goods_material_category = GoodsMaterialCategory::create([
                        'goods_id' => $goods_id,
                        'category_id' => $category['material_category_id'],
                        'all_choice' => (isset($category['choice']) && $category['choice'] == 'on') ? 1 : 0,
                        'must_select' => (isset($category['must_select']) && $category['must_select'] == 'on') ? 1 : 0,
                    ]);

                    foreach ($category['material'] as $material) {
                        $material_data[] = [
                            'goods_id' => $goods_id,
                            'material_id' => $material,
                            'material_category_id' => $goods_material_category->id,
                        ];
                    }
                }
                $goods_material = new GoodsMaterial;
                $goods_material->saveAll($material_data);
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 获取商品分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 10:47 上午
     */
    public static function goodsCategoryLists()
    {
        return GoodsCategory::where('del',0)->field('id,name')->select()->toArray();
    }

    /**
     * @notes 获取物料分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/3 11:51 上午
     */
    public static function getMaterialCategoryLists()
    {
        return MaterialCategory::where('del',0)->with(['material'])->field('id,name')->select()->toArray();
    }

    /**
     * @notes 添加商品主表
     * @param $post
     * @return mixed
     * @throws \think\Exception
     * @author ljj
     * @date 2021/8/30 2:05 下午
     */
    public static function addGoods($post)
    {
        //算出最大最小价格
        if ($post['spec_type'] == 1) {
            $max_price = $post['one_price'];
            $min_price = $post['one_price'];
            $market_price = $post['one_market_price'];
        } else { // 多规格
            $max_price = max($post['price']);
            $min_price = min($post['price']);
            $market_price = max($post['market_price']);
        }

        //写入主表
        $data = [
            'name'                      => trim($post['name']),
            'code'                      => $post['code'] ? trim($post['code']) : create_goods_code(),
            'goods_category_id'         => $post['goods_category_id'],
            'status'                    => $post['status'],
            'image'                     => $post['image'],
            'remark'                    => $post['remark'] ?? '',
            'sort'                      => $post['sort'] ?? DefaultEnum::SORT,
            'spec_type'                 => $post['spec_type'],
            'max_price'                 => $max_price,
            'min_price'                 => $min_price,
            'market_price'              => $market_price,
            'create_time'               => time(),
        ];
        $goods = Goods::create($data);
        return $goods->id;
    }

    /**
     * @notes 添加统一规格
     * @param $goods_id
     * @param $post
     * @author ljj
     * @date 2021/8/30 2:16 下午
     */
    public static function addOneSpec($goods_id, $post)
    {
        //添加商品规格
        $goods_spec_id = (new GoodsSpec())->insertGetId([
            'goods_id' => $goods_id,
            'name' => '默认'
        ]);

        //添加商品规格值
        $goods_spec_value_id = (new GoodsSpecValue())->insertGetId([
            'spec_id' => $goods_spec_id,
            'goods_id' => $goods_id,
            'value' => '默认'
        ]);

        //商品sku
        GoodsItem::create([
            'goods_id'          => $goods_id,
            'spec_value_ids'    => $goods_spec_value_id,
            'spec_value_str'    => '默认',
            'market_price'      => $post['one_market_price'],
            'price'             => $post['one_price'],
        ]);
    }

    /**
     * @notes 添加多个规格
     * @param $goods_id
     * @param $post
     * @param $spec_lists
     * @author ljj
     * @date 2021/8/30 2:16 下午
     */
    public static function addMoreSpec($goods_id, $post, $spec_lists)
    {
        // 添加规格项
        $goods_specs = [];
        foreach ($post['spec_name'] as $k => $v) {
            $temp = ['goods_id' => $goods_id, 'name' => $v];
            $goods_specs[] = $temp;
        }
        (new GoodsSpec())->insertAll($goods_specs);

        // 规格项id及名称 例：['颜色'=>1, '尺码'=>2]
        $goods_spec_name_key_id = GoodsSpec::where(['goods_id' => $goods_id])
            ->where('name', 'in', $post['spec_name'])
            ->column('id', 'name');

        // 添加规格值
        $data = [];
        foreach ($post['spec_values'] as $k => $v) {
            $row = explode(',', $v);
            foreach ($row as $k2 => $v2) {
                $temp = [
                    'goods_id' => $goods_id,
                    'spec_id' => $goods_spec_name_key_id[$post['spec_name'][$k]],
                    'value' => $v2,
                ];
                $data[] = $temp;
            }
        }
        (new GoodsSpecValue())->insertAll($data);

        // 规格值id及名称   例：['红色'=>1,'蓝色'=>2,'S码'=>3,'M码'=>4]
        $goods_spec_name_value_id = GoodsSpecValue::where(['goods_id' => $goods_id])->column('id', 'value');

        // 添加SKU
        foreach ($spec_lists as $k => $v) {
            $spec_lists[$k]['spec_value_ids'] = '';
            $temp = explode(',', $v['spec_value_str']); // 例："红色,S码" => ["红色", "S码"]

            // 组装SKU的spec_value_ids 例："红色,S码" => ["红色", "S码"] => "1,3"
            foreach ($temp as $k2 => $v2) {
                $spec_lists[$k]['spec_value_ids'] .= $goods_spec_name_value_id[$v2] . ',';
            }
            $spec_lists[$k]['spec_value_ids'] = trim($spec_lists[$k]['spec_value_ids'], ',');
            $spec_lists[$k]['goods_id'] = $goods_id;
            unset($spec_lists[$k]['spec_id']);
            unset($spec_lists[$k]['item_id']);
        }

        (new GoodsItem())->insertAll($spec_lists);
    }

    /**
     * @notes 修改商品状态
     * @param $post
     * @return bool
     * @throws \Exception
     * @author ljj
     * @date 2021/8/30 3:18 下午
     */
    public static function changeStatus($post)
    {
        try {
            //修改商品状态
            $goods = new Goods;
            $data = [];
            foreach ($post['goods_ids'] as $val) {
                $data[] = [
                    'id' => $val,
                    'status' => $post['status'],
                ];
            }
            $goods->saveAll($data);

            if ($post['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                //下架对应的门店商品
                $shop_goods_ids = ShopGoods::where('goods_id', 'in', implode(',',$post['goods_ids']))->column('id');
                $data = [];
                foreach ($shop_goods_ids as $val) {
                    $data[] = ['id'=>$val, 'status'=>DefaultConfigEnum::NO];
                }
                $shop_goods = new ShopGoods;
                $shop_goods->saveAll($data);
            }

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            self::$error = $e->getMessage();
            return false;
        }
    }


    public static function delStatus($post)
    {
        try {
            //修改商品状态
            $goods = new Goods;
            $data = [];
            foreach ($post['goods_ids'] as $val) {
                $data[] = [
                    'id' => $val,
                    'del' => 1,
                ];
            }
            $goods->saveAll($data);

            return true;
        } catch (\Exception $e) {
            // 回滚事务
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * @notes 导出列表
     * @param $get
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 3:44 下午
     */
    public static function exportFile($get)
    {
        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.shop_cate_id','=', $get['goods_category']];
        }

        $type = $get['type'] ?: 0;

        switch ($type) {
            case 2:      //总部可售
                $where[] = ['g.status', '=', GoodsEnum::STATUS_SHELVES];
                break;
            case 3:     //总部停售
                $where[] = ['g.status', '=', GoodsEnum::STATUS_SOLD_OUT];
                break;
            default:
                break;
        }

        $goods_list_export = Goods::alias('g')
            ->where($where)
            ->Join('goods_category gc', 'gc.id=g.goods_category_id')
            ->field('g.id, g.image, g.name, gc.name as goods_category_name, g.min_price, g.max_price, g.status, g.sort, g.create_time')
            ->order(['g.sort'=>'asc','g.create_time'=>'desc'])
            ->select();

        $exportTitle = ['商品名称', '商品分类', 'SKU最低价', 'SKU最高价', '销量', '总部商品状态', '排序', '发布时间'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($goods_list_export as $item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部可售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }
            $item['sales_actual'] = ShopGoods::where('goods_id',$item['id'])->sum('sales_sum');

            $exportData[] = [$item['name'], $item['goods_category_name'], $item['min_price'], $item['max_price'], $item['sales_actual'], $item['status_desc'], $item['sort'], $item['create_time']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'商品列表'.date('Y-m-d H:i:s',time())];
    }

    /**
     * @notes 编辑商品
     * @param $post
     * @param $spec_lists
     * @return bool
     * @throws \think\db\exception\DbException
     * @author ljj
     * @date 2021/8/30 4:18 下午
     */
    public static function edit($post, $spec_lists)
    {
        try {

            Db::startTrans();
            $goods = Goods::where('id', $post['goods_id'])->find();

            //计算最大最小价格
            if ($post['spec_type'] == 1) {
                $max_price = $post['one_price'];
                $min_price = $post['one_price'];
                $market_price = $post['one_market_price'];
            } else {
                $max_price = max($post['price']);
                $min_price = min($post['price']);
                $market_price = max($post['market_price']);
            }



            //更新主表
            $data = [
                'name'                      => trim($post['name']),
                'code'                      => trim($post['code']) ?? create_goods_code(),
                'goods_category_id'         => $post['goods_category_id'],
                'status'                    => $post['status'],
                'image'                     => $post['image'],
                'remark'                    => $post['remark'],
                'sort'                      => $post['sort'],
                'spec_type'                 => ($goods['status'] == 0) ? $post['spec_type'] : $goods['spec_type'],
                'max_price'                 => $max_price,
                'min_price'                 => $min_price,
                'market_price'              => $market_price,
                'update_time'               => time(),
            ];
            $goods->save($data);

            //下架状态才可以修改商品规格信息
            if (($goods['status'] == 0)) {
                //写入规格表
                if ($post['spec_type'] == 1) {
                    //单规格写入
                    if ($goods['spec_type'] == 1) {
                        //原来是单规格
                        $data = [
                            'market_price'      => $post['one_market_price'],
                            'price'             => $post['one_price'],
                        ];
                        GoodsItem::where(['goods_id' => $post['goods_id']])->update($data);
                    } else {
                        //原来多规格
                        //删除多规格
                        GoodsSpec::where('goods_id', $post['goods_id'])->delete();
                        GoodsSpecValue::where('goods_id', $post['goods_id'])->delete();
                        GoodsItem::where('goods_id', $post['goods_id'])->delete();
                        //删除门店商品规格信息
                        ShopGoodsItem::where('goods_id', $post['goods_id'])->delete();


                        //重新添加商品规格信息
                        $goodsSpec = GoodsSpec::create(['goods_id' => $post['goods_id'], 'name' => '默认']);
                        $goods_spec_id = $goodsSpec->id;
                        $goodsSpecValue = GoodsSpecValue::create(['spec_id' => $goods_spec_id, 'goods_id' => $post['goods_id'], 'value' => '默认']);
                        $goods_spec_value_id = $goodsSpecValue->id;
                        $data = [
                            'goods_id'          => $post['goods_id'],
                            'spec_value_ids'    => $goods_spec_value_id,
                            'spec_value_str'    => '默认',
                            'market_price'      => $post['one_market_price'],
                            'price'             => $post['one_price'],
                        ];
                        $goods_item = GoodsItem::create($data);

                        //重新添加门店商品规格信息
                        $shop_goods = ShopGoods::where('goods_id',$post['goods_id'])->select()->toArray();
                        $data = [];
                        foreach ($shop_goods as $val) {
                            $data[] = [
                                'shop_id' => $val['shop_id'],
                                'goods_id' => $post['goods_id'],
                                'shop_goods_id' => $val['id'],
                                'item_id' => $goods_item->id,
                            ];
                        }
                        $shop_goods_item = new ShopGoodsItem;
                        $shop_goods_item->saveAll($data);
                    }
                } else {
                    // 多规格写入
                    $goods_specs = [];
                    foreach ($post['spec_name'] as $k => $v) {
                        $temp = ['goods_id' => $post['goods_id'], 'name' => $v, 'spec_id' => $post['spec_id'][$k]];
                        $goods_specs[] = $temp;
                    }
                    $new_spec_name_ids = [];
                    foreach ($goods_specs as $k => $v) {
                        if ($v['spec_id']) {
                            //更新规格名
                            GoodsSpec::where(['goods_id' => $post['goods_id'], 'id' => $v['spec_id']])
                                ->update(['name' => $v['name']]);
                            $new_spec_name_ids[] = $v['spec_id'];
                        } else {
                            //添加规格名
                            $goodsSpec = GoodsSpec::create(['goods_id' => $post['goods_id'], 'name' => $v['name']]);
                            $new_spec_name_ids[] = $goodsSpec->id;
                        }
                    }
                    //删除规格项
                    $all_spec_ids = GoodsSpec::where('goods_id', $post['goods_id'])->column('id');
                    $del_spec_name_ids = array_diff($all_spec_ids, $new_spec_name_ids);
                    if (!empty($del_spec_name_ids)) {
                        GoodsSpec::where('goods_id', $post['goods_id'])
                            ->where('id', 'in', $del_spec_name_ids)
                            ->delete();
                    }

                    $new_spec_value_ids = [];
                    $goods_spec_name_key_id = Db::name('goods_spec')
                        ->where(['goods_id' => $post['goods_id']])
                        ->where('name', 'in', $post['spec_name'])
                        ->column('id', 'name');
                    foreach ($post['spec_values'] as $k => $v) {
                        $value_id_row = explode(',', $post['spec_value_ids'][$k]);
                        $value_row = explode(',', $v);
                        foreach ($value_row as $k2 => $v2) {
                            $temp = [
                                'goods_id' => $post['goods_id'],
                                'spec_id' => $goods_spec_name_key_id[$post['spec_name'][$k]],
                                'value' => $v2,
                            ];
                            if ($value_id_row[$k2]) {
                                //更新规格值
                                Db::name('goods_spec_value')
                                    ->where(['id' => $value_id_row[$k2]])
                                    ->update($temp);
                                $new_spec_value_ids[] = $value_id_row[$k2];
                            } else {
                                //添加规格值
                                $new_spec_value_ids[] = Db::name('goods_spec_value')
                                    ->insertGetId($temp);
                            }
                        }
                    }
                    $all_spec_value_ids = Db::name('goods_spec_value')
                        ->where('goods_id', $post['goods_id'])
                        ->column('id');
                    $del_spec_value_ids = array_diff($all_spec_value_ids, $new_spec_value_ids);
                    if (!empty($del_spec_value_ids)) {
                        //删除规格值
                        Db::name('goods_spec_value')
                            ->where('goods_id', $post['goods_id'])
                            ->where('id', 'in', $del_spec_value_ids)
                            ->delete();
                    }

                    $new_item_id = [];
                    $goods_spec_name_value_id = Db::name('goods_spec_value')
                        ->where(['goods_id' => $post['goods_id']])
                        ->column('id', 'value');
                    foreach ($spec_lists as $k => $v) {
                        $spec_lists[$k]['spec_value_ids'] = '';
                        $temp = explode(',', $v['spec_value_str']);
                        foreach ($temp as $k2 => $v2) {
                            $spec_lists[$k]['spec_value_ids'] .= $goods_spec_name_value_id[$v2] . ',';
                        }
                        $spec_lists[$k]['spec_value_ids'] = trim($spec_lists[$k]['spec_value_ids'], ',');
                        $spec_lists[$k]['goods_id'] = $post['goods_id'];
                        unset($spec_lists[$k]['spec_id']);
                        $item_id = $spec_lists[$k]['item_id'];
                        unset($spec_lists[$k]['item_id']);
                        if ($item_id) {
                            Db::name('goods_item')
                                ->where(['id' => $item_id])
                                ->update($spec_lists[$k]);
                            $new_item_id[] = $item_id;
                        } else {
                            $item_id = Db::name('goods_item')
                                ->insertGetId($spec_lists[$k]);
                            $new_item_id[] = $item_id;

                            //添加门店商品规格信息
                            $shop_goods = ShopGoods::where('goods_id',$post['goods_id'])->select()->toArray();
                            $data = [];
                            foreach ($shop_goods as $val) {
                                $data[] = [
                                    'shop_id' => $val['shop_id'],
                                    'goods_id' => $post['goods_id'],
                                    'shop_goods_id' => $val['id'],
                                    'item_id' => $item_id,
                                ];
                            }
                            $shop_goods_item = new ShopGoodsItem;
                            $shop_goods_item->saveAll($data);
                        }
                    }
                    $all_item_id = Db::name('goods_item')
                        ->where('goods_id', $post['goods_id'])
                        ->column('id');
                    $del_item_ids = array_diff($all_item_id, $new_item_id);
                    if (!empty($del_item_ids)) {
                        //删除规格值
                        Db::name('goods_item')
                            ->where('goods_id', $post['goods_id'])
                            ->where('id', 'in', $del_item_ids)
                            ->delete();

                        //删除门店商品规格信息
                        ShopGoodsItem::where('item_id', 'in', $del_item_ids)->delete();
                    }
                }
            }

            //下架状态才可以修改商品物料信息
            if (($goods['status'] == 0)) {
                //删除旧的商品物料
                GoodsMaterial::where(['goods_id' => $post['goods_id']])->delete();
                GoodsMaterialCategory::where(['goods_id' => $post['goods_id']])->delete();

                //添加新的商品物料
                if (isset($post['material_category'])) {
                    $material_data = [];
                    foreach ($post['material_category'] as $category) {
                        $goods_material_category = GoodsMaterialCategory::create([
                            'goods_id' => $post['goods_id'],
                            'category_id' => $category['material_category_id'],
                            'all_choice' => (isset($category['choice']) && $category['choice'] == 'on') ? 1 : 0,
                            'must_select' => (isset($category['must_select']) && $category['must_select'] == 'on') ? 1 : 0,
                        ]);

                        foreach ($category['material'] as $material) {
                            $material_data[] = [
                                'goods_id' => $post['goods_id'],
                                'material_id' => $material,
                                'material_category_id' => $goods_material_category->id,
                            ];
                        }
                    }
                    $goods_material = new GoodsMaterial;
                    $goods_material->saveAll($material_data);
                }
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }

    /**
     * 获取商品信息
     * @param $goods_id
     * @return array
     */
    public static function info($goods_id)
    {
        // 商品主表
        $info['base'] = Goods::where(['id' => $goods_id])
            ->withAttr('abs_image', function ($value, $data) {
                return UrlServer::getFileUrl($data['image']);
            })
            ->withAttr('poster', function ($value){
                return empty($value) ? '' : UrlServer::getFileUrl($value);
            })
            ->append(['abs_image','abs_video'])->find();
        // 商品SKU
        $info['item'] =GoodsItem::where(['goods_id' => $goods_id])->select();
        // 商品规格项
        $info['spec'] = GoodsSpec::where(['goods_id' => $goods_id])->select();
        // 商品规格值
        $spec_value = GoodsSpecValue::where(['goods_id' => $goods_id])->select();

        $data = [];
        foreach ($spec_value as $k => $v) {
            $data[$v['spec_id']][] = $v;
        }
        foreach ($info['spec'] as $k => $v) {
            $info['spec'][$k]['values'] = isset($data[$v['id']]) ? $data[$v['id']] : [];
        }

        return ['info'=>$info,'status'=>$info['base']->status];
    }

    //获取商品物料信息
    public static function getGoodsMaterial($goods_id)
    {
        return [
            'goods_material' => GoodsMaterial::where('goods_id',$goods_id)->select(),
            'goods_material_category' => GoodsMaterialCategory::where('goods_id',$goods_id)->select(),
        ];
    }

}
