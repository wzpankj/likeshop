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

// | Author: LikeShop-段誉
// +----------------------------------------------------------------------


namespace app\admin\controller\setting;


use app\admin\logic\NoticeSettingLogic;
use app\common\basics\AdminBase;
use app\common\enum\NoticeEnum;
use app\common\server\JsonServer;
use think\Db;

/**
 * 通知设置
 * Class NoticeSetting
 * @package app\admin\controller\setting
 */
class NoticeSetting extends AdminBase
{

    /**
     * Notes: 消息设置列表
     * @author 段誉(2021/4/27 17:17)
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            $type = $get['type'] ?? NoticeEnum::NOTICE_USER;
            return JsonServer::success('获取成功', NoticeSettingLogic::lists($type));
        }
        return view('', [
            'type_list' => NoticeEnum::getNoticeType(true)
        ]);
    }



    /**
     * Notes: 设置系统通知模板
     * @author 段誉(2021/4/27 17:18)
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function set()
    {
        $id = $this->request->get('id');
        $type = $this->request->get('type');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            NoticeSettingLogic::set($post);
            return JsonServer::success('操作成功');
        }
        return view('set_'.$type, [
            'info' => NoticeSettingLogic::info($id, $type),
            'type' => $type
        ]);
    }

    /**
     * 通知记录
     */
    public function record()
    {
        if($this->request->isAjax()) {
            $get = $this->request->get();
            $data = NoticeSettingLogic::record($get);
            return JsonServer::success('', $data);
        }
        $param = $this->request->get();
        return view('', ['param' => $param]);
    }

    /**
     * 删除记录，直接删除（非软删除）
     */
    public function delRecord()
    {
        $id = $this->request->post('id', '', 'intval');
        if(empty($id)) {
            return JsonServer::error('参数缺失,删除失败');
        }
        $res = Db::name('notice')->delete($id);
        if(!$res) {
            return JsonServer::error('删除失败');
        }
        return JsonServer::success('删除成功');
    }
}