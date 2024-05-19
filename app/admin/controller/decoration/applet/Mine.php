<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------

namespace app\admin\controller\decoration\applet;


use app\admin\logic\decoration\applet\MineLogic;
use app\admin\validate\decoration\applet\MineValidate;
use app\common\basics\AdminBase;
use app\common\enum\MenuEnum;
use app\common\server\JsonServer;

/**
 * 我的装修控制器
 * Class Mine
 * @package app\admin\controller\decoration\applet
 */
class Mine extends AdminBase
{
    /**
     * @notes 我的装修列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 3:21 下午
     */
    public function lists()
    {
        if($this->request->isAjax()){
            $list = MineLogic::lists();
            return JsonServer::success('',$list);
        }

        return view('',['other_set'=>MineLogic::getOtherSet()]);
    }

    /**
     * @notes 其他设置
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 3:59 下午
     */
    public function otherSet()
    {
        $post = $this->request->post();
        MineLogic::otherSet($post);
        return JsonServer::success('设置成功');
    }

    /**
     * @notes 添加
     * @return \think\response\Json|\think\response\View
     * @author ljj
     * @date 2021/10/9 4:47 下午
     */
    public function add()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new MineValidate())->goCheck('Add',$post);
            $result = MineLogic::add($post);
            if($result){
                return JsonServer::success('添加成功');
            }
            return JsonServer::error('添加失败');

        }

        return view('',['menu_list'=>MenuEnum::CENTRE]);
    }

    /**
     * @notes 编辑
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 5:06 下午
     */
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new MineValidate())->goCheck('edit',$post);
            $result = MineLogic::edit($post);

            if($result){
                return JsonServer::success('修改成功');
            }
            return JsonServer::error('修改失败');
        }

        $id = $this->request->get('id');
        return view('',['menu_list'=>MenuEnum::CENTRE,'detail'=>MineLogic::getDetail($id)]);
    }

    /**
     * @notes 修改状态
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/9 5:09 下午
     */
    public function status()
    {
        (new MineValidate())->goCheck('status');
        $post = $this->request->post();
        MineLogic::status($post);
        return JsonServer::success('操作成功');
    }

    /**
     * @notes 删除
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/9 5:12 下午
     */
    public function del()
    {
        $id = $this->request->post('id');
        (new MineValidate())->goCheck('del');
        MineLogic::del($id);
        return JsonServer::success('删除成功');
    }
}