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
use app\common\enum\GoodsEnum;
use app\common\enum\ShopGoodsEnum;
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsCategory;
use app\common\model\Material;
use app\common\model\MaterialCategory;
use app\common\model\shop\ShopGoods;
use app\common\model\shop\ShopGoodsItem;
use app\common\server\UrlServer;

/**
 * 门店商品-逻辑层
 * Class
 * @package
 */
class ShopGoodsLogic extends Logic
{
    /**
     * @notes 门店商品列表
     * @param $get
     * @param $admin_id
     * @return array
     * @author ljj
     * @date 2021/9/1 11:46 上午
     */
    public static function lists($get,$admin_id)
    {
        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        $where[] = ['sg.shop_id', '=', $admin_id];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.goods_category_id','=', $get['goods_category']];
        }

        $type = $get['type'] ?: 0;

        switch ($type) {
            case 2:      //门店在售
                $where[] = ['sg.status', '=', ShopGoodsEnum::STATUS_SHELVES];
                break;
            case 3:     //门店售罄
                $where[] = [['sg.total_stock', '=', 0],['sg.status', '=', ShopGoodsEnum::STATUS_SHELVES]];
                break;
            case 4:     //门店停售
                $where[] = ['sg.status', '=', ShopGoodsEnum::STATUS_SOLD_OUT];
                break;
            default:
                break;
        }

        $lists = Goods::alias('g')
            ->Join('shop_goods sg', 'g.id = sg.goods_id')
            ->field('sg.id, g.image, g.name, g.min_price, g.max_price, sg.total_stock, sg.sales_sum,sg.status as shop_status, g.status,g.sort')
            ->where($where)
            ->page($get['page'], $get['limit'])
            ->order(['g.sort'=>'asc','g.id'=>'desc'])
            ->select();

        $count = Goods::alias('g')->Join('shop_goods sg', 'g.id = sg.goods_id')->where($where)->count();

        foreach($lists as &$item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部在售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }
            switch($item['shop_status']) {
                case 1:
                    $item['shop_status_desc'] = '门店在售';
                    break;
                case 0:
                    $item['shop_status_desc'] = '门店停售';
                    break;
            }
        }
        if($count) {
            $lists = $lists->toArray();
        }else{
            $lists = [];
        }
        return ['count' => $count, 'lists' => $lists];
    }

    /**
     * @notes 商品统计
     * @param $admin_id
     * @return int[]
     * @author ljj
     * @date 2021/9/1 11:52 上午
     */
    public static function statistics($admin_id){
        $where = [
            ['g.del', '=', GoodsEnum::DEL_NORMAL],
            ['sg.shop_id', '=', $admin_id]
        ];
        $where[] = ['sg.shop_id', '=', $admin_id];
        $goods_list = Goods::alias('g')
            ->Join('shop_goods sg', 'g.id = sg.goods_id')
            ->where($where)
            ->field('sg.status,sg.total_stock')
            ->select();

        $goods = [
            'all'      => 0,             //全部
            'on_sale' => 0,              //门店在售
            'sell_out' => 0,             //门店售罄
            'stop_selling'   => 0,       //门店停售
        ];

        foreach ($goods_list as $item){
            // 全部
            $goods['all']++;

            // 门店在售
            if($item['status'] == GoodsEnum::STATUS_SHELVES) {
                $goods['on_sale']++;
            }

            // 门店售罄
            if($item['total_stock'] == 0 && $item['status'] == GoodsEnum::STATUS_SHELVES) {
                $goods['sell_out']++;
            }

            // 门店停售
            if($item['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                $goods['stop_selling']++;
            }
        }

        return $goods;
    }

    /**
     * @notes 获取商品列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/1 11:53 上午
     */
    public static function goodsCategoryLists()
    {
        return GoodsCategory::where('del',0)->field('id,name')->select()->toArray();
    }

    /**
     * @notes 导出列表
     * @param $get
     * @param $admin_id
     * @return array
     * @author ljj
     * @date 2021/9/1 2:19 下午
     */
    public static function exportFile($get,$admin_id)
    {
        $where = [];
        $where[] = ['g.del', '=', GoodsEnum::DEL_NORMAL];
        $where[] = ['sg.shop_id', '=', $admin_id];
        if(isset($get['goods_name']) && !($get['goods_name'] == '')) {
            $where[] = ['g.name','like','%'.$get['goods_name'].'%'];
        }
        if(!empty($get['goods_category'])) {
            $where[] = ['g.goods_category_id','=', $get['goods_category']];
        }

        $type = $get['type'] ?: 0;

        switch ($type) {
            case 2:      //门店在售
                $where[] = ['sg.status', '=', ShopGoodsEnum::STATUS_SHELVES];
                break;
            case 3:     //门店售罄
                $where[] = ['sg.total_stock', '=', 0];
                break;
            case 4:     //门店停售
                $where[] = ['sg.status', '=', ShopGoodsEnum::STATUS_SOLD_OUT];
                break;
            default:
                break;
        }

        $lists = Goods::alias('g')
            ->Join('shop_goods sg', 'g.id = sg.goods_id')
            ->field('g.id, g.image, g.name, g.min_price, g.max_price, sg.total_stock, sg.sales_sum,sg.status as shop_status, g.status,g.sort')
            ->where($where)
            ->order(['g.sort'=>'asc','g.create_time'=>'desc'])
            ->select();

        $exportTitle = ['商品名称', 'SKU最低价', 'SKU最高价', '门店库存', '门店销量', '门店商品状态', '总部商品状态', '排序'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            switch($item['status']) {
                case 1:
                    $item['status_desc'] = '总部在售';
                    break;
                case 0:
                    $item['status_desc'] = '总部停售';
                    break;
            }
            switch($item['shop_status']) {
                case 1:
                    $item['shop_status_desc'] = '门店在售';
                    break;
                case 0:
                    $item['shop_status_desc'] = '门店停售';
                    break;
            }

            $exportData[] = [$item['name'], $item['min_price'], $item['max_price'], $item['total_stock'], $item['sales_sum'], $item['shop_status_desc'], $item['status_desc'], $item['sort']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'商品列表'.date('Y-m-d H:i:s',time())];
    }

    /**
     * @notes 下架商品
     * @param $post
     * @return \think\Collection
     * @throws \Exception
     * @author ljj
     * @date 2021/9/1 2:29 下午
     */
    public static function lower($post)
    {
        $shop_goods = new ShopGoods;
        $data = [];
        foreach ($post['goods_ids'] as $val) {
            $data[] = ['id'=>$val,'status'=>ShopGoodsEnum::STATUS_SOLD_OUT];
        }
        return $shop_goods->saveAll($data);
    }

    /**
     * @notes 上架商品
     * @param $post
     * @return \think\Collection
     * @throws \Exception
     * @author ljj
     * @date 2021/9/1 2:45 下午
     */
    public static function upper($post)
    {
        $shop_goods = new ShopGoods;
        $data = [];
        foreach ($post['goods_ids'] as $val) {
            $data[] = ['id'=>$val,'status'=>ShopGoodsEnum::STATUS_SHELVES];
        }
        return $shop_goods->saveAll($data);
    }

    /**
     * @notes 获取商品详情
     * @param $goods_id
     * @param $admin_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/1 8:32 下午]
     */
    public static function info($goods_id)
    {
        $info = Goods::alias('g')
            ->join('shop_goods sg', 'sg.goods_id = g.id')
            ->join('goods_category gc', 'gc.id = g.goods_category_id')
            ->join('shop s', 's.id = sg.shop_id')
            ->with(['goods_item','goods_material','goods_material_category'])
            ->field('g.id,sg.id as shop_goods_id,g.code,g.name,g.remark,gc.name as goods_category_name,g.image,g.sort,g.status,s.pricing_policy')
            ->where('sg.id',$goods_id)
            ->find()
            ->toArray();

        //处理商品规格信息
        foreach ($info['goods_item'] as &$val) {
            $shop_goods_item = ShopGoodsItem::where(['item_id'=>$val['id'],'shop_goods_id'=>$info['shop_goods_id']])->find()->toArray();
            $val['stock'] = $shop_goods_item['stock'];
            $val['id'] = $shop_goods_item['id'];
            if ($info['pricing_policy'] == 2) {
                $val['price'] = $shop_goods_item['price'];
            }
        }

        //处理商品物料信息
        $goods_material = array_column($info['goods_material'],'material_id');
        foreach ($info['goods_material_category'] as &$category) {
            $category['name'] = MaterialCategory::where('id',$category['category_id'])->value('name');
            $category['goods_material'] = Material::where('id','in',$goods_material)->where('material_category_id',$category['category_id'])->select()->toArray();
        }

        return $info;
    }

    /**
     * @notes 获取物料分类列表
     * @return mixed
     * @author ljj
     * @date 2021/9/6 3:50 下午
     */
    public static function getMaterialCategoryLists()
    {
        return MaterialCategory::where('del',0)->with(['material'])->field('id,name')->select()->toArray();
    }

    /**
     * @notes 编辑商品
     * @param $post
     * @return \think\Collection
     * @throws \Exception
     * @author ljj
     * @date 2021/9/1 8:49 下午
     */
    public static function edit($post)
    {

        try {
            // 修改门店商品SKU信息
            $shop_goods_item = new ShopGoodsItem;
            $data = [];
            $stock = [];
            foreach ($post['goods_item'] as $val) {
                $stock[] = $val['stock'];
                $data[] = [
                    'id' => $val['id'],
                    'price' => $val['price'],
                    'stock' => $val['stock'],
                ];
            }
            $shop_goods_item->saveAll($data);

            //修改门店商品总库存
            $shop_goods = ShopGoods::find($post['goods_id']);
            $shop_goods->total_stock = array_sum($stock);
            $shop_goods->save();


            return true;
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }
}