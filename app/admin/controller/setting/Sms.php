<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------
namespace app\admin\controller\setting;


use app\admin\logic\setting\SmsLogic;
use app\common\basics\AdminBase;
use app\common\enum\SmsEnum;
use app\common\server\ConfigServer;
use app\common\server\JsonServer;

/**
 * 短信设置
 * Class Sms
 * @package app\admin\controller\setting
 */
class Sms extends AdminBase
{

    /**
     * Notes: 列表
     * @author 段誉(2021/6/7 14:46)
     * @return \think\response\Json|\think\response\View
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $lists = SmsLogic::configLists();
            return JsonServer::success('获取成功', $lists);
        }
        return view('', ['status_list' => SmsEnum::getSendStatusDesc(true)]);
    }

    /**
     * Notes: 短信配置
     * @author 段誉(2021/6/7 14:46)
     * @return \think\response\Json|\think\response\View
     */
    public function config()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $res = SmsLogic::setConfig($post);
            if (false === $res) {
                return JsonServer::error(SmsLogic::getError());
            }
            return JsonServer::success('设置成功');
        }
        $engine = $this->request->get('engine');
        $info = SmsLogic::getConfigInfo($engine);
        if (false === $info) {
            return JsonServer::error('数据错误');
        }
        return view('', [
            'engine'    => $engine,
            'info'      => $info
        ]);
    }




    /**
     * Notes: 短信记录->列表
     * @author 段誉(2021/6/7 14:46)
     * @return \think\response\Json
     */
    public function logLists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $lists = SmsLogic::logLists($get);
            return JsonServer::success('', $lists);
        }
    }

    /**
     * Notes: 短信记录->详情
     * @author 段誉(2021/6/7 14:46)
     * @return \think\response\View
     */
    public function detail()
    {
        $id = $this->request->get('id');
        $info = SmsLogic::detail($id);
        return view('', ['info' => $info]);
    }
}