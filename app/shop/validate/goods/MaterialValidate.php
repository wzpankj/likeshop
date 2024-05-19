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
use app\common\model\Material;

/**
 * 门店物料验证器
 * Class MaterialValidate
 * @package app\shop\validate\goods
 */
class MaterialValidate extends Validate
{
    protected $rule = [
        'id' => 'require|checkId',
        'shop_price' => 'require',
    ];

    protected $message = [
        'id.require' => '参数缺失',
        'shop_price.require' => '门店物料价格不能为空',
    ];

    public function sceneEdit()
    {
        return $this->only(['shop_price']);
    }

    /**
     * @notes 检验物料id是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/6 5:24 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Material::where('id',$value)->findOrEmpty();
        if ($result->isEmpty()) {
            return '物料不存在';
        }
        return true;
    }
}