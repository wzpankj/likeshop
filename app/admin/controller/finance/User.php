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

namespace app\admin\controller\finance;

use app\common\basics\AdminBase;
use app\common\server\JsonServer;
use app\admin\logic\finance\UserLogic;

/**
 * 财务-用户相关
 * Class User
 * @package app\admin\controller\finance
 */
class User extends AdminBase
{
    /**
     * @notes 余额明细列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author heshihu
     * @date 2021/9/14 10:38
     */
    public function account()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $data = UserLogic::account($get);
            return JsonServer::success('', $data, 1);
        }

        return view();
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/12 3:02 下午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',UserLogic::exportFile($get));
    }

    /**
     * @notes 资产概况
     * @return \think\response\View
     * @author ljj
     * @date 2021/10/12 3:17 下午
     */
    public function overview()
    {
        return view('',['detail'=>UserLogic::overview()]);
    }
}