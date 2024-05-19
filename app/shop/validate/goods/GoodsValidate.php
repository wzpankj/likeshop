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

namespace app\shop\validate\goods;


use app\common\basics\Validate;
use app\common\enum\GoodsEnum;
use app\common\model\goods\Goods;

/**
 * 门店平台商品库验证器
 * Class GoodsValidate
 * @package app\shop\validate\goods
 */
class GoodsValidate extends Validate
{
    protected $rule = [
        'goods_ids' => 'require|array',
    ];

    public function sceneJoinShopGoods()
    {
        return $this->only(['goods_ids'])
            ->append('goods_ids','checkGoodsIds');
    }

    public function sceneRemoveShopGoods()
    {
        return $this->only(['goods_ids']);
    }

    /**
     * @notes 检验商品是否允许加入门店
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/8/31 5:24 下午
     */
    public function checkGoodsIds($value,$rule,$data)
    {
        foreach ($value as $val) {
            $result = Goods::where(['id'=>$val,'del'=>0])->findOrEmpty();
            if ($result->isEmpty()) {
                return '存在非法商品，请重新提交!';
            }
            if ($result['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                return '存在平台已下架商品，操作失败!';
            }
        }

        return true;
    }

}