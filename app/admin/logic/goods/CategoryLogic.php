<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likemarket/likeshopv2
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\admin\logic\goods;

use app\common\model\goods\GoodsCategory as GoodsCategoryModel;
use app\common\server\UrlServer;

/**
 * 平台商品分类-逻辑层
 * Class CategoryLogic
 * @package app\admin\logic\goods
 */
class CategoryLogic
{
  /**
   *  获取分类列表(所有)
   */
  public static function lists()
  {
    $lists = GoodsCategoryModel::field('id,name,image,sort')
      ->where('del', 0)
      ->order(['sort'=>'asc','id'=>'desc'])
      ->select()
      ->toArray();

    // 线性结构转树形结构(顶级分类树)
    //$lists = linear_to_tree($lists);
    return $lists;
  }



  /**
   * 添加分类
   */
  public static function add($post)
  {
    $data = [
      'name'              => trim($post['name']),
      'sort'              => $post['sort'],
      'image'             => isset($post['image']) ? clearDomain($post['image']) : '',
      'create_time'       => time(),
    ];
    return GoodsCategoryModel::create($data);
  }

    /**
     * 分类详情
     */
    public static function getCategory($id)
    {
        $detail = GoodsCategoryModel::where([
            'del' => 0,
            'id' => $id
        ])->find();

        return $detail;
    }


    /**
     *  编辑
     */
    public static function edit($post)
    {
        $data = [
            'name'              => $post['name'],
            'sort'              => $post['sort'],
            'image'             => isset($post['image']) ? clearDomain($post['image']) : '',
            'update_time'       => time(),
        ];
        return GoodsCategoryModel::where(['id'=>$post['id']])->update($data);
    }


  /**
   * 删除分类
   */
  public static function del($post)
  {
    return GoodsCategoryModel::update([
      'id' => $post['id'],
      'del' => 1,
      'update_time' => time(),
    ]);
  }





  /**
   * 平台商品分类（三级）
   */
  public static function categoryTreeeTree()
  {
    $lists = GoodsCategoryModel::where(['del' => 0])->column('id,name,pid,level', 'id');
    return self::cateToTree($lists, 0, '|-----', 1);
  }

}
