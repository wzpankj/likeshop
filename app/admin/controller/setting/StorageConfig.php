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
// | Author: LikeShopTeam
// +----------------------------------------------------------------------


namespace app\admin\controller\setting;


use app\admin\logic\setting\StorageConfigLogic;
use app\common\basics\AdminBase;
use app\common\server\ConfigServer;
use app\common\server\JsonServer;

/**
 * 上传设置
 * Class StorageConfig
 * @package app\admin\controller
 */
class StorageConfig extends AdminBase
{
    /**
     * @notes 存储引擎列表
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/9/24 15:38
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $data = StorageConfigLogic::lists();
            return JsonServer::success('获取成功', ['lists' => $data]);
        }
        return view();
    }

    /**
     * @notes 编辑存储引擎
     * @return \think\response\Json|\think\response\View
     * @author cjhao
     * @date 2021/9/24 15:40
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $result = StorageConfigLogic::edit($post);
            if(true === $result){
                return JsonServer::success('设置成功');
            }
            return JsonServer::error($result);
        }

        $engine = $this->request->get('engine');
        $storage = StorageConfigLogic::getStorage();
        return view('', [
            'engine' => $engine,
            'storage' => $storage,
        ]);
    }

    /**
     * @notes 切换存储引擎
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/24 15:40
     */
    public function changeEngine()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            StorageConfigLogic::changeEngine($post);
            return JsonServer::success('切换成功');
        }
    }
}