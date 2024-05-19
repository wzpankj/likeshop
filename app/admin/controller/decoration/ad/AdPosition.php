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

use app\admin\logic\decoration\ad\AdPositionLogic;
use app\admin\validate\decoration\ad\AdPositionValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

/**
 * 广告位控制器
 * Class AdPosition
 * @package app\admin\controller\decoration\ad
 */
class AdPosition extends AdminBase
{
    /**
     * @notes 广告位列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/11 6:08 下午
     */
    public function lists(){
        if($this->request->isAjax()){
            $get = $this->request->get();
            $list = AdPositionLogic::lists($get);
            return JsonServer::success('',$list);
        }
        return view();
    }

    /**
     * @notes 添加广告位
     * @return \think\response\Json|\think\response\View
     * @author ljj
     * @date 2021/10/11 6:32 下午
     */
    public function add()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new AdPositionValidate())->goCheck('add',$post);

            $result = AdPositionLogic::add($post);
            if(true === $result){
                return JsonServer::success('添加成功');
            }
            return JsonServer::error('添加失败');
        }
        return view();
    }

    /**
     * @notes 编辑广告位
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/11 6:46 下午
     */
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new AdPositionValidate())->goCheck('edit',$post);

            AdPositionLogic::edit($post);
            return JsonServer::success('修改成功');

        }

        $id = $this->request->get('id');
        return view('',['detail'=>AdPositionLogic::getDetail($id)]);
    }

    /**
     * @notes 删除广告位
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/11 6:58 下午
     */
    public function del()
    {
        $post = $this->request->post();
        (new AdPositionValidate())->goCheck('del',$post);

        AdPositionLogic::del($post['id']);
        return JsonServer::success('删除成功');


    }

    /**
     * @notes 更新广告位状态
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/11 7:02 下午
     */
    public function status()
    {
        $post = $this->request->post();
        (new AdPositionValidate())->goCheck('status',$post);

        AdPositionLogic::status($post);
        return JsonServer::success('操作成功');


    }



}