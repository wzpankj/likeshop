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


namespace app\admin\controller\system;

use app\common\basics\AdminBase;
use app\admin\logic\system\CrontabLogic;
use app\common\model\system\DevCrontab;
use app\common\server\JsonServer;
use app\common\server\system\CrontabServer;
use think\exception\ValidateException;
use app\admin\validate\system\CrontabValidate;
use Cron\CronExpression;

/**
 * 定时任务
 * Class Crontab
 * @package app\admin\controller
 */
class Crontab extends AdminBase
{
    public function lists()
    {
        if ($this->request->isAjax()) {
            $data = CrontabLogic::lists();
            return JsonServer::success('', $data);
        }
        return view();
    }

    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['status'] = isset($post['status']) && $post['status'] == 'on' ? 1 : 2;
            try{
                validate(CrontabValidate::class)->scene('add')->check($post);
            }catch(ValidateException $e) {
                return JsonServer::error($e->getError());
            }
            $result = CrontabLogic::add($post);
            if($result === true) {
                return JsonServer::success('添加成功');
            }
            return JsonServer::error(CrontabLogic::getError());
        }
        return view();
    }

    /**
     * 获取接下来执行时间
     */
    public function expression()
    {
        $get = $this->request->get();
        return JsonServer::success('', CrontabLogic::expression($get));
    }

    /**
     * 编辑定时任务
     * @return mixed
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $post['status'] = isset($post['status']) && $post['status'] == 'on' ? 1 : 2;
            try{
                validate(CrontabValidate::class)->check($post);
            }catch(ValidateException $e) {
                return JsonServer::error($e->getError());
            }
            $result = CrontabLogic::edit($post);
            if($result === true) {
                return JsonServer::success('编辑成功');
            }
            return JsonServer::error(CrontabLogic::getError());
        }

        $id = $this->request->get('id');
        return view('', [
            'info' => CrontabLogic::info($id)
        ]);
    }

    public function operation()
    {
        $post = $this->request->post();
        $result = CrontabLogic::operation($post['operation'], $post['id']);
        if ($result === true) {
            return JsonServer::success('操作成功');
        }
        return JsonServer::error(CrontabLogic::getError());
    }

    public function del()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('id');
            $result = CrontabLogic::del($id);
            if($result === true) {
                return JsonServer::success('删除成功');
            }
            return JsonServer::error(CrontabLogic::getError());
        }
    }
}