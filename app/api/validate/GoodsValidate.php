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

namespace app\api\validate;


use app\common\basics\Validate;
use app\common\model\goods\GoodsCategory;
use app\common\model\shop\Shop;

/**
 * 商品验证器
 * Class GoodsValidate
 * @package app\api\validate
 */
class GoodsValidate extends Validate
{
    protected $rule = [
        'shop_id' => 'require|checkShopId',
        'category_id' => 'checkCategoryId',
    ];

    protected $message = [
        'shop_id.require' => '参数缺失',
    ];

    public function sceneLists()
    {
        return $this->only(['shop_id','category_id']);
    }

    /**
     * @notes 检验门店是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/7 2:07 下午
     */
    public function checkShopID($value,$rule,$data)
    {
        $result = Shop::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '门店不存在';
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
     * @date 2021/9/7 2:26 下午
     */
    public function checkCategoryId($value,$rule,$data)
    {
        $result = GoodsCategory::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '商品分类不存在';
        }
        return true;
    }
}