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

namespace app\shop\controller\goods;


use app\common\basics\ShopBase;
use app\common\server\JsonServer;
use app\shop\logic\goods\MaterialLogic;
use app\shop\validate\goods\MaterialValidate;

/**
 * 门店物料控制器
 * Class Material
 * @package app\shop\controller\goods
 */
class Material extends ShopBase
{
    /**
     * @notes 门店物料列表
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/6 5:20 下午
     */
    public function lists()
    {
        if ($this->request->isAjax()) {
            $get = $this->request->get();
            return JsonServer::success('', MaterialLogic::lists($get,$this->shop_id));
        }
        return view();
    }

    /**
     * @notes 修改门店物料
     * @return \think\response\Json|\think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/6 5:48 下午
     */
    public function edit()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            (new MaterialValidate())->goCheck('edit');

            $result = MaterialLogic::edit($post,$this->shop_id);

            if (!$result) {
                return JsonServer::error(MaterialLogic::getError() ?: '操作失败');
            }
            return JsonServer::success('操作成功');
        }

        $id = $this->request->get('id');
        return view('', [
            'detail' => MaterialLogic::detail($id,$this->shop_id),
        ]);
    }
}