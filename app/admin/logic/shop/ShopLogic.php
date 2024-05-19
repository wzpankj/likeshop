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
namespace app\admin\logic\shop;
use app\common\enum\DefaultConfigEnum;
use app\common\enum\ShopEnum;
use app\common\model\shop\{
    Shop,
    ShopAdmin
};
use think\{
    Exception,
    facade\Db,
    facade\Cache
};

/**
 * Class ShopLogic 门店逻辑层
 * @package app\admin\logic\shop
 */
class ShopLogic
{

    /**
     * @notes 门店列表
     * @param array $get
     * @return array
     * @throws \think\db\exception\DbException
     * @author cjhao
     * @date 2021/8/30 9:59
     */
    public static function lists(array $get):array
    {
        $where = [];
        if(isset($get['name']) && $get['name'])
        {
            $where[] = ['name','like','%'.$get['name'].'%'];
        }
        if(isset($get['pricing_policy']) && $get['pricing_policy']){
            $where[] = ['pricing_policy','=',$get['pricing_policy']];
        }
        $lists = Shop::where($where)
                ->field('id,shop_sn,name,status,pricing_policy,create_time')
                ->order('id','desc')
                ->limit($get['page'],$get['limit'])
                ->paginate([
                    'page'      => $get['page'],
                    'list_rows' => $get['limit'],
                    'var_page'  => 'page'
                ]);
        $shop_admin_list = ShopAdmin::where(['root'=>1])->column('id,account','shop_id');

        foreach ($lists as $shop){
            $shop['status_desc'] = DefaultConfigEnum::getShopStatusDesc($shop['status']);
            $shop['admin_id']    = $shop_admin_list[$shop['id']]['id'] ?? '';
            $shop['account']     = $shop_admin_list[$shop['id']]['account'] ?? '';
            $shop['pricing_policy_desc'] = ShopEnum::getPricingPriceDesc($shop['pricing_policy']);
        }
        $lists = $lists->toArray();

        return ['count'=>$lists['total'], 'lists'=>$lists['data']];
    }

    /**
     * @notes 添加门店
     * @param array $post
     * @return array
     * @author cjhao
     * @date 2021/8/30 17:48
     */
    public static function add(array $post)
    {
        Db::startTrans();
        try{
            $now = time();

            $shop = new Shop();
            $shop->name                 = $post['name'];
            $shop->shop_sn              = $post['shop_sn'];
            $shop->contact              = $post['contact'];
            $shop->phone                = $post['phone'];
            $shop->province_id          = $post['province_id'];
            $shop->city_id              = $post['city_id'];
            $shop->district_id          = $post['district_id'];
            $shop->address              = $post['address'];
            $shop->longitude            = $post['longitude'];
            $shop->latitude             = $post['latitude'];
            $shop->business_start_time  = $post['business_start_time'];
            $shop->business_end_time    = $post['business_end_time'];
            $shop->weekdays             = implode(',',$post['weekdays']);
            $shop->delivery_distance    = $post['delivery_distance'];
            $shop->delivery_buy_limit   = $post['delivery_buy_limit'];
            $shop->delivery_freight     = $post['delivery_freight'];
            $shop->pricing_policy       = $post['pricing_policy'];
            $shop->delivery_type        = implode(',',$post['delivery_type']);
            $shop->status               = $post['status'];
            $shop->save();


            $time = time();
            $salt = substr(md5($time . $post['account']), 0, 4);//随机4位密码盐

            //门店管理员
            $shop_admin = new ShopAdmin();
            $shop_admin->shop_id    = $shop->id;
            $shop_admin->name       = $post['name'];
            $shop_admin->account    = $post['account'];
            $shop_admin->password   = generatePassword($post['password'], $salt);
            $shop_admin->salt       = $salt;
            $shop_admin->create_time= $now;
            $shop_admin->update_time= $now;
            $shop_admin->save();

            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            return $e->getMessage();
        }
    }

    /**
     * @notes 门店详情
     * @param int $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/30 19:26
     */
    public static function detail(int $id):array
    {

        $detail = Shop::where(['id'=>$id,'del'=>0])
                ->find()->toArray();
        $detail['weekdays'] = explode(',',$detail['weekdays']);
        $detail['delivery_type'] = explode(',',$detail['delivery_type']);
        return $detail;

    }

    /**
     * @notes 修改门店
     * @param array $post
     * @return Shop
     * @author cjhao
     * @date 2021/8/30 20:14
     */
    public static function edit(array $post){
        $data  = [
            'name'                 => $post['name'],
            'shop_sn'              => $post['shop_sn'],
            'contact'              => $post['contact'],
            'phone'                => $post['phone'],
            'province_id'          => $post['province_id'],
            'city_id'              => $post['city_id'],
            'district_id'          => $post['district_id'],
            'address'              => $post['address'],
            'longitude'            => $post['longitude'],
            'latitude'             => $post['latitude'],
            'business_start_time'  => $post['business_start_time'],
            'business_end_time'    => $post['business_end_time'],
            'weekdays'             => implode(',',$post['weekdays']),
            'delivery_distance'    => $post['delivery_distance'],
            'delivery_buy_limit'   => $post['delivery_buy_limit'],
            'delivery_freight'     => $post['delivery_freight'],
            'pricing_policy'       => $post['pricing_policy'],
            'delivery_type'        => implode(',',$post['delivery_type']),
            'status'               => $post['status'],
            'update_time'          => time(),
        ];
        return Shop::update($data,['id'=>$post['id']]);
    }

    /**
     * @notes 设置单点登录数据
     * @param int $id
     * @return array
     * @author cjhao
     * @date 2021/8/31 9:42
     * 1，获取shop_id,生成随机code
     * 2，把随机code保存在缓存cache，以shop_id_+$shop_id 为键名
     * 3，跳转到门店模块 链接带shop_id 和加密的code
     * 4，门店模块检查 缓存中 加密的code和 跳转链接的code是否一致，同时检查门店是否设置管理员，没设置则管理员，没设置跳转到登录页，
     */
    public static function shopSso(int $id):array
    {
        //生成随机code
        $rand = mt_rand(1000000000, 9999999999);
        $time = time();
        $code = $time . $rand;
        Cache::set('shop_id_' . $id, $code, 10);//缓存10秒
        session('shop_info', null);
        return ['shop_id'=>$id,'code'=>$code];
    }

    /**
     * @notes 获取账号
     * @param $id
     * @return array
     * @author cjhao
     * @date 2021/8/31 11:45
     */
    public static function getAccountInfo($id)
    {
        $detail = ShopAdmin::field('id,account')->where('shop_id', $id)->findOrEmpty()->toArray();
        return $detail;
    }

    /**
     * @notes 修改账号密码
     * @param array $post
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/8/31 12:02
     */
    public static function account(array $post)
    {

        $shop_admin = ShopAdmin::where('id', $post['id'])->find();
        $update_data = [
            'account'     => $post['account'],
            'update_time' => time()
        ];
        if (!empty($post['password'])) {
            $update_data['password'] = generatePassword($post['password'], $shop_admin->salt);
        }
        return $shop_admin->save($update_data);
    }

    public static function updateStatus(array $post)
    {
        return Shop::where(['id'=>$post['id']])->update(['update_time'=>time(),'status'=>$post['status']]);
    }


}