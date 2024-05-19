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

/**
 * 平台商品多规格验证器
 * Class GoodsMoreSpec
 * @package app\admin\validate\goods
 */
class GoodsMoreSpec extends Validate
{
    protected $rule = [
        'spec_name' => 'require|array|specNameRepetition',
        'spec_values' => 'require|array|specValueRepetition',
    ];

    protected $message = [];

    /**
     * 检测规格名称是否重复
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    public function specNameRepetition($value, $rule, $data)
    {
        if (count($value) != count(array_unique($value))) {
            return '规格名称重复';
        }
        return true;
    }

    public function specValueRepetition($value, $rule, $data)
    {
        foreach ($value as $k => $v) {
            $row = explode(',', $v);
            if (count($row) != count(array_unique($row))) {
                return '同一规格项的规格值不能重复';
            }
        }
        return true;
    }

}