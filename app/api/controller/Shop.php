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
namespace app\api\controller;
use app\common\{basics\Api, server\JsonServer};
use app\api\logic\ShopLogic;
use \think\facade\Db;

/**
 * 门店控制器
 * Class Shop
 * @package app\api\controller
 */
class Shop extends Api
{
    public $like_not_need_login = ['lists','announcement','one','shopinfo'];

    /**
     * @notes 门店列表
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/1 11:49
     */
    public function lists()
    {
        $get = $this->request->get();
        $data = ShopLogic::lists($get,$this->page_no,$this->page_size);
        return JsonServer::success('', $data);
    }

    /**
     * @notes 门店公告
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/19 6:23 下午
     */
    public function announcement()
    {
        return JsonServer::success('', ShopLogic::announcement());
    }

    /**
     * 获取单个店铺信息
     */
    public function shopinfo(){
        $get = $this->request->get();
        $data = ShopLogic::listsone(['type'=>1,'latitude'=>$get['latitude'],'longitude'=>$get['longitude']],$this->page_no,$this->page_size);
        return JsonServer::success('', $data);
    }
    /**
     * 获取单个店铺信息
     */
    public function one(){
        $get = $this->request->get();
        $data = Db::name('shop')->where('id',intval($get['shop_id']))->find();
        return JsonServer::success('', $data);
    }

}