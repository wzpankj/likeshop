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

namespace app\admin\logic\printer;

use app\common\basics\Logic;
use app\common\model\PrinterTemplate;

/**
 * 小票模板逻辑层
 * Class TemplateLogic
 * @package app\admin\logic\printer
 */
class TemplateLogic extends Logic
{
    /**
     * @notes 小票模板列表
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/29 11:04 上午
     */
    public static function lists()
    {
        $lists = PrinterTemplate::where(['del'=>0])
            ->order('id desc')
            ->select()
            ->toArray();

        return ['count' => 0,'lists' => $lists];
    }

    /**
     * @notes 添加小票模板
     * @param $post
     * @param $admin_id
     * @return bool
     * @author ljj
     * @date 2021/9/29 11:50 上午
     */
    public static function add($post)
    {
        PrinterTemplate::create([
            'name'                          => $post['name'],
            'title'                         => $post['title'],
            'is_qrCode'                     => $post['is_qrCode'],
            'qrCode_link'                   => $post['qrCode_link'] ?? '',
            'bottom'                        => $post['bottom'] ?? '',
            'create_time'                   => time(),
            'update_time'                   => time(),
        ]);

        return true;
    }

    /**
     * @notes 获取小票模板详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/29 11:53 上午
     */
    public static function getDetail($id)
    {
        return PrinterTemplate::where(['del'=>0,'id'=>$id])->find()->toArray();
    }

    /**
     * @notes 编辑小票模板
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/9/29 11:59 上午
     */
    public static function edit($post)
    {
        PrinterTemplate::update([
            'name'                          => $post['name'],
            'title'                         => $post['title'],
            'is_qrCode'                     => $post['is_qrCode'],
            'qrCode_link'                   => $post['qrCode_link'],
            'bottom'                        => $post['bottom'],
            'update_time'                   => time(),
        ], ['id' => $post['id']]);

        return true;
    }

    /**
     * @notes 删除小票模板
     * @param $id
     * @return bool
     * @author ljj
     * @date 2021/9/29 12:01 下午
     */
    public static function del($id)
    {
        PrinterTemplate::update([
            'del'                        => 1,
            'update_time'                => time(),
        ], ['id' => $id]);

        return true;
    }

}