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
namespace app\admin\controller\decoration\ad;

use app\admin\logic\decoration\ad\AdLogic;
use app\admin\validate\decoration\ad\AdValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

class Ad extends AdminBase
{
    /**
     * @notes 广告列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/12 10:35 上午
     */
    public function lists()
    {
        if($this->request->isAjax()){
            $get = $this->request->get();
            $list = AdLogic::lists($get);
            return JsonServer::success('',$list);
        }
        return view('',[
            'position_list' => ADLogic::getPositionList(),
        ]);
    }

    /**
     * @notes 添加广告
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/12 10:58 上午
     */
    public function add(){
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new AdValidate())->goCheck('add',$post);

            $result = AdLogic::add($post);
            if(true === $result){
                return JsonServer::success('添加成功');
            }
            return JsonServer::error('添加失败');
        }

        return view('',['position_list'=>AdLogic::getPositionList()]);
    }

    /**
     * @notes 编辑广告
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/12 11:02 上午
     */
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new AdValidate())->goCheck('edit',$post);
            AdLogic::edit($post);
            return JsonServer::success('修改成功');
        }

        $id = $this->request->get('id');
        return view('',['detail'=>AdLogic::getDetail($id),'position_list'=>AdLogic::getPositionList()]);
    }

    /**
     * @notes 删除广告
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/12 11:08 上午
     */
    public function del()
    {
        $post = $this->request->post();
        (new AdValidate())->goCheck('del',$post);

        AdLogic::del($post['id']);
        return JsonServer::success('删除成功');
    }

    /**
     * @notes 修改广告状态
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/12 11:12 上午
     */
    public function status()
    {
        $post = $this->request->post();
        (new AdValidate())->goCheck('status',$post);

        AdLogic::status($post);
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 导出列表
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/12 11:19 上午
     */
    public function exportFile()
    {
        $get = $this->request->get();
        return JsonServer::success('',AdLogic::exportFile($get));
    }
}