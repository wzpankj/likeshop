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

namespace app\api\validate;
use app\common\{model\Cart, basics\Validate, model\shop\Shop};


/**
 * 购物车验证类
 * Class CartValidate
 * @package app\api\validate
 */
class CartValidate extends Validate
{
    protected $rule = [
        'cart_id'       => 'require',
        'cart_ids'      => 'require',
        'shop_id'       => 'require|checkShop',
        'item_id'       => 'require',
        'num'           => 'require|integer|gt:0',
    ];

    protected $message = [
        'cart_id.require'   => '参数错误',
        'cart_ids.require'  => '参数错误',
        'item_id.require'   => '请选择商品',
        'shop_id.require'   => '请选择门店',
        'num.require'       => '商品数量不能为0',
        'num.gt'            => '商品数量需大于0',
        'num.integer'       => '商品数量需为整数',
    ];

    protected function sceneAdd()
    {
        return $this->only(['shop_id','item_id','material_ids']);
    }
    protected function sceneChange()
    {
        return $this->only(['cart_id','num']);
    }
    protected function sceneDel()
    {
        return $this->only(['cart_ids']);
    }
    protected function sceneLists()
    {
        return $this->only(['shop_id']);
    }

    protected function checkShop($value,$rule,$data)
    {
        $shop = Shop::where(['del'=>0,'id'=>$value])->find();
        if(empty($shop)){
            return '门店不存在';
        }
        return true;
    }

    protected function checkCart($value, $rule, $data)
    {
        $cart = Cart::where(['id' => $value, 'user_id' => $data['user_id']])->find();
        if (!$cart) {
            return '购物车不存在';
        }
        return true;
    }


}