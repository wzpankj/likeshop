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
 * 门店商品验证器
 * Class ShopGoodsValidate
 * @package app\shop\validate\goods
 */
class ShopGoodsValidate extends Validate
{
    protected $rule = [
        'goods_ids' => 'require|array',
        'goods_id' => 'require',
    ];

    public function sceneLower()
    {
        return $this->only(['goods_ids']);
    }

    public function sceneUpper()
    {
        return $this->only(['goods_ids'])
            ->append('goods_ids','checkGoodsIds');
    }

    public function sceneEdit()
    {
        return $this->only(['goods_id']);
    }

    /**
     * @notes 检验上架商品状态
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/1 2:44 下午
     */
    public function checkGoodsIds($value,$rule,$data)
    {
        foreach ($value as $val) {
            $result = Goods::alias('g')
                ->join('shop_goods sg', 'sg.goods_id = g.id')
                ->where(['sg.id'=>$val,'g.del'=>0])
                ->field('g.status,sg.total_stock')
                ->find()
                ->toArray();

            if (empty($result)) {
                return '存在非法商品，请重新提交!';
            }
            if ($result['status'] == GoodsEnum::STATUS_SOLD_OUT) {
                return '存在平台已下架商品，请重新提交!';
            }
            if ($result['total_stock'] == 0) {
                return '存在库存不足商品，无法上架!';
            }
        }

        return true;
    }

}