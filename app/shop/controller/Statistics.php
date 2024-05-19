<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\shop\controller;


use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\StatisticsLogic;


/**
 * 数据统计
 * Class Statistics
 * @package app\admin\controller
 */
class Statistics extends ShopBase
{
   
    //交易分析
    public function trading()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            $res = StatisticsLogic::trading($post,$this->shop_id);
            return JsonServer::success('',$res);
        }
        return view();
    }


    //访问分析
    public function visit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            $res = StatisticsLogic::visit($post,$this->shop_id);
            return JsonServer::success('',$res);
        }
        return view();
    }


    //商品分析
    public function goods()
    {
        if($this->request->isAjax()){
            $get= $this->request->get();
            $res = StatisticsLogic::goods($get,$this->shop_id);
            return JsonServer::success('',$res);
        }
        return view();
    }

}