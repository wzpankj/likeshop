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
use app\common\model\PrinterConfig;

/**
 * 打印设置逻辑层
 * Class ConfigLogic
 * @package app\admin\logic\printer
 */
class ConfigLogic extends Logic
{
    /**
     * @notes 获取打印设置详情
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/29 2:43 下午
     */
    public static function getDetail()
    {
        $printer_config = PrinterConfig::where('id','=',1)->find();

        if ($printer_config) {
            $printer_config = $printer_config->toArray();
        }else {
            $printer_config = [];
        }

        return $printer_config;
    }

    /**
     * @notes 编辑打印设置
     * @param $post
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/29 3:02 下午
     */
    public static function edit($post)
    {
        $result = PrinterConfig::where('id','=',1)->find();

        if (!$result) {
            PrinterConfig::create([
                'name'  => $post['name'],
                'client_id' => $post['client_id'],
                'client_secret' => $post['client_secret'],
                'update_time' => time(),
            ]);
        }else {
            $result->name = $post['name'];
            $result->client_id = $post['client_id'];
            $result->client_secret = $post['client_secret'];
            $result->update_time = time();
            $result->save();
        }

        return true;
    }

}