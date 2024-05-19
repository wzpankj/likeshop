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


namespace app\admin\controller\system;

use app\admin\logic\system\UpgradeLogic;
use app\admin\validate\UpgradeValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

/**
 * 升级更新
 * Class Upgrade
 * @package app\admin\controller
 */
class Upgrade extends AdminBase
{

    /**
     * Notes: 更新列表页
     * @author 段誉(2021/7/12 16:17)
     * @return \think\response\Json|\think\response\View
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = UpgradeLogic::index($this->page_no, $this->page_size);
            return JsonServer::success('', $data);
        }
        return view();
    }


    /**
     * Notes: 提示
     * @author 段誉(2021/7/12 16:17)
     * @return \think\response\View
     */
    public function choosePage()
    {
        return view();
    }


    /**
     * Notes: 执行更新
     * @author 段誉(2021/7/12 16:18)
     * @return \think\response\Json
     */
    public function handleUpgrade()
    {
        if ($this->request->isAjax()) {
            (new UpgradeValidate())->goCheck();
            $post = $this->request->post();
            $res = UpgradeLogic::upgrade($post);
            if (true === $res) {
                return JsonServer::success('更新成功');
            }
        }
        return JsonServer::error($res);
    }


    /**
     * Notes: 添加日志
     * @author 段誉(2021/7/12 16:18)
     * @return \think\response\Json
     */
    public function addLog()
    {
        $post = $this->request->post();
        $post['update_type'] = 2; // 更新类型为->下载更新包
        $res = UpgradeLogic::addlog($post);
        if($res === false) {
            return JsonServer::error('添加更新日志失败');
        }
        return JsonServer::success('添加更新日志成功');
    }

    /**
     * @notes 更新包下载链接
     */
    public function downloadPkg()
    {
        $post = $this->request->post();
        $result = UpgradeLogic::getPkgLine($post);
        if (false === $result) {
            return JsonServer::error('获取更新包下载链接失败，请先联系客服确保已获取授权');
        }
        return JsonServer::success('',$result);
    }
}