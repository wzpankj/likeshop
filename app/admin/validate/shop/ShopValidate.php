<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\admin\validate\shop;
use app\common\{
    basics\Validate,
    model\shop\Shop,
    model\shop\ShopAdmin
};

/**
 * 门店验证器
 * Class ShopValidate
 * @package app\admin\validate\shop
 */
class ShopValidate  extends Validate{

    protected $rule = [
        'id'                    => 'require',
        'shop_sn'               => 'require|max:16|unique:'.Shop::class.',shop_sn^del',
        'name'                  => 'require|max:16|unique:'.Shop::class.',name^del',
        'contact'               => 'require|max:10',
        'phone'                 => 'require|mobile',
        'province_id'           => 'require',
        'city_id'               => 'require',
        'district_id'           => 'require',
        'address'               => 'require|max:64',
        'longitude'             => 'require',
        'latitude'              => 'require',
        'business_start_time'   => 'require',
        'business_end_time'     => 'require|checkTime',
        'weekdays'              => 'require|array',
        'delivery_type'         => 'require|array',
        'delivery_distance'     => 'require|number',
        'delivery_buy_limit'    => 'require|egt:0',
        'delivery_freight'      => 'require|egt:0',
        'pricing_policy'        => 'require|in:1,2',
        'status'                => 'require|in:0,1',
        'account'               => 'require|length:4,16|unique:'.ShopAdmin::class.',account^del',
        'password'              => 'require|length:4,16|confirm',
    ];

    protected $message = [
        'id.require'                    => '请选择门店',
        'name.require'                  => '请输入门店名称',
        'name.max'                      => '门店名称长度不能超过16字符',
        'name.unique'                   => '门店名称重复，请重新输入',
        'shop_sn.require'               => '请输入门店编号',
        'shop_sn.max'                   => '门店编号长度不能超过16字符',
        'shop_sn.unique'                => '门店编号重复，请重新输入',
        'contact.require'               => '请输入联系人',
        'contact.max'                   => '联系人长度不能超过16字符',
        'phone.require'                 => '请输入联系人手机',
        'phone.mobile'                  => '手机格式错误',
        'province_id.require'           => '请选择省份',
        'city_id.require'               => '请选择城市',
        'district_id.require'           => '请选择地区',
        'address.require'               => '请输入详情地址',
        'address.max'                   => '详情地址不能超过64字符',
        'longitude.require'             => '请在地图上标记地址',
        'latitude.require'              => '请在地图上标记地址',
        'business_start_time.require'   => '请输入营业时段',
        'business_end_time.require'     => '请输入营业时段',
        'weekdays.require'              => '请勾选营业周天',
        'weekdays.array'                => '营业周天数据错误',
        'delivery_type.require'         => '请选择配送方式',
        'delivery_type.array'           => '选择配送数据错误',
        'delivery_distance.require'     => '请输入外卖配送距离',
        'delivery_buy_limit.require'    => '请输入外卖起送价',
        'delivery_freight.require'      => '请输入外卖配送运费',
        'delivery_distance.number'      => '外卖配送距离必须是纯数字',
        'delivery_buy_limit.egt'        => '外卖起送价不能小于零',
        'delivery_freight.egt'          => '外卖配送运费不能小于零',
        'pricing_policy.require'        => '请选择价格策略',
        'pricing_policy.in'             => '价格策略数据错误',
        'status.require'                => '请选择门店状态',
        'status.in'                     => '门店状态数据错误',
        'account.require'               => '请输入管理员账号',
        'account.unique'                => '管理员账号已存在',
        'account.length'                => '管理员账号长度在4~16个字符',
        'password.require'              => '请输入管理员密码',
        'password.length'               => '管理员密码长度在4~16个字符',
        'password.confirm'              => '登录密码和确认密码不一致',
    ];

    public function sceneAdd()
    {
        return $this->remove('id',['require']);
    }

    public function sceneEdit()
    {
        return $this->remove('account','require|length:4,16|unique')
                    ->remove('password','require|length:4,16|confirm');
    }

    public function sceneAccount()
    {
        return $this->only(['account']);
    }

    public function scenePwd()
    {
        return $this->only(['password']);
    }

    public function sceneStatus()
    {
        return $this->only(['id']);
    }


    public function checkTime($value,$rule,$data)
    {
        if($data['business_start_time'] > $value){
            // return '营业时段错误';
        }
        return true;
    }

}
