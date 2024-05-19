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

namespace app\admin\logic\decoration\applet;


use app\common\basics\Logic;
use app\common\model\DecorateHome;

/**
 * 首页装修逻辑层
 * Class HomeLogic
 * @package app\admin\logic\decoration\applet
 */
class HomeLogic extends Logic
{
    /**
     * @notes 首页装修列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 2:11 下午
     */
    public static function lists()
    {
        $lists = DecorateHome::order('id','asc')->select();

        return ['count'=>0,'lists'=>$lists];
    }

    /**
     * @notes 编辑
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/9 2:33 下午
     */
    public static function edit($post)
    {
        DecorateHome::update([
            'name' => $post['name'],
            'image' => $post['image'],
            'describe' => $post['describe'],
        ], ['id' => $post['id']]);

        return true;
    }

    /**
     * @notes 获取详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 2:34 下午
     */
    public static function getDetail($id)
    {
        return DecorateHome::find($id)->toArray();
    }
}