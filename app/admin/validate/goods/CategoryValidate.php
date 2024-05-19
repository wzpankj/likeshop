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

namespace app\admin\validate\goods;

use app\common\basics\Validate;
use app\common\model\goods\Goods;
use app\common\model\goods\GoodsCategory as GoodsCategoryModel;

/**
 * 平台商品分类验证器
 * Class CategoryValidate
 * @package app\admin\validate\goods
 */
class CategoryValidate extends Validate
{
    protected $rule = [
        'id'    => 'require|checkId',
        'name'  => 'require|max: 30|checkName',
    ];

    protected $message = [
        'id.require'  => 'id不能为空',
        'name.require'  => '分类名称不能为空',
        'name.max'  => '分类名称不能超过30个字符',
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
     * @notes 检验分类名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/8/30 5:23 下午
     */
    protected function checkName($value,$rule,$data){
    $where[] = ['del','=',0];
    // 如果有id代表是编辑校验分类名称
    if(isset($data['id'])){
        $where[] = ['id','<>',$data['id']];
    }
    $where[] = ['name','=',$value];

    $result = GoodsCategoryModel::where($where)->findOrEmpty();
    if(!$result->isEmpty()){
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
     * @date 2021/8/30 5:24 下午
     */
    protected function checkId($value,$rule,$data)
    {
        $result = GoodsCategoryModel::where(['del'=>0,'id'=>$value])->findOrEmpty();
        if($result->isEmpty()){
            return '分类不存在';
        }
        return true;
    }

    /**
     * @notes 检验分类是否能删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/8/30 5:31 下午
     */
    protected function checkDel($value,$rule,$data)
    {
        $result = Goods::where(['goods_category_id'=>$value])->select()->toArray();
        if(!empty($result)){
            return '分类正在使用中，无法删除';
        }
        return true;
    }
}