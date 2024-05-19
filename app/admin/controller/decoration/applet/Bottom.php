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


use app\admin\logic\decoration\applet\BottomLogic;
use app\admin\validate\decoration\applet\BottomValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

/**
 * 底部装修控制器
 * Class Bottom
 * @package app\admin\controller\decoration\applet
 */
class Bottom extends AdminBase
{
    /**
     * @notes 底部装修列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 6:52 下午
     */
    public function lists()
    {
        if($this->request->isAjax()){
            $list = BottomLogic::lists();
            return JsonServer::success('',$list);
        }
        return view();
    }

    /**
     * @notes 编辑
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 7:10 下午
     */
    public function edit()
    {
        if($this->request->isAjax()) {
            $post = $this->request->post();
            (new BottomValidate())->goCheck('edit',$post);
            $result = BottomLogic::edit($post);
            if($result) {
                return JsonServer::success('修改成功');
            }

            return JsonServer::error('修改失败');
        }

        $id = $this->request->get('id');
        return view('', [
            'detail' => BottomLogic::getDetail($id)
        ]);
    }
}