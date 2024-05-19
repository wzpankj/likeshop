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

namespace app\admin\controller\printer;

use app\admin\logic\printer\ConfigLogic;
use app\admin\validate\printer\ConfigValidate;
use app\common\basics\AdminBase;
use app\common\server\JsonServer;

/**
 * 打印设置控制器
 * Class Config
 * @package app\admin\controller\printer
 */
class Config extends AdminBase
{
    /**
     * @notes 打印设置列表
     * @return \think\response\View
     * @author ljj
     * @date 2021/9/29 2:26 下午
     */
    public function lists()
    {
        return view();
    }

    /**
     * @notes 编辑打印设置
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/29 3:03 下午
     */
    public function edit()
    {
        if($this->request->isAjax()){
            $post = $this->request->post();
            (new ConfigValidate())->goCheck('edit',$post);

            ConfigLogic::edit($post);
            return JsonServer::success('修改成功');
        }

        return view('', [
            'detail' => ConfigLogic::getDetail(),
        ]);
    }

}