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
namespace app\common\listener;

use app\common\model\order\Order;
use app\common\model\PrinterConfig;
use app\common\server\YlyPrinter;
use think\Exception;
use think\facade\Cache;
use app\common\server\FeiePrinter;
use think\facade\Db;
class Printer
{
    /**
     * @notes 自动打印订单
     * @param $params
     * @return bool|string
     * @author ljj
     * @date 2021/9/29 6:26 下午
     */
    public function handle($params)
    {
        try{
            //获取打印订单
            $order = Order::with(['user', 'order_goods','shop'])
                ->where('id', $params['order_id'])
                ->append(['delivery_address','dining_type_text'])
                ->find()
                ->toArray();
            $order['shop_name'] = $order['shop']['name'];
            $order['goods_price'] = $order['total_amount'] - $order['delivery_amount'];
            foreach ($order['order_goods'] as &$val) {
                $info = $val['goods_snap'];
                $val['name'] = $info['name'];
                $val['spec_value_str'] = $info['spec_value_str'];
                $val['goods_image'] = empty($info['spec_image']) ? $info['image'] : $info['spec_image'];
            }

            //打印机配置 todo 条件暂时写死
            $printer_config = PrinterConfig::where(['id' => 1])->find();
            //打印机列表
            $printer_list = \app\common\model\Printer::with(['printer_template'])->where(['printer_config_id' => $printer_config['id'], 'del' => 0,'status'=>1,'auto_print'=>1])->select();

            if (empty($printer_config) || empty($printer_list)) {
                return false;
            }
            $feie=new FeiePrinter;
            define('USER', $printer_config['client_id']);  //*必填*：飞鹅云后台注册账号
            define('UKEY', $printer_config['client_secret']);  //*必填*: 飞鹅云后台注册账号后生成的UKEY 【备注：这不是填打印机的KEY】
            //以下参数不需要修改
            define('IP','api.feieyun.cn');      //接口IP或域名
            define('PORT',80);            //接口IP端口
            define('PATH','/Api/Open/');    //接口路径
   //          DB::name('debug_log')->insert(['spec'    => "70".serialize(json_encode(USER))]);
			// DB::name('debug_log')->insert(['spec'    => "71".serialize(json_encode(UKEY))]);
            // $yly_print = new YlyPrinter($printer_config['client_id'], $printer_config['client_secret']);

            // $yly_print->ylyPrint($printer_list, $order);
            $feie->ylyPrint($printer_list, $order);
            return true;

        }catch (\Exception $e){

            $msg = json_decode($e->getMessage(), true);
            if ($msg && isset($msg['error'])) {
                return '易联云：' . $msg['error_description'];
            }
            if (18 === $e->getCode()) {
                //todo token过期重新拿
                Cache::rm('yly_access_token');
                Cache::rm('yly_refresh_token');
            }
            return '易联云：' . $e->getMessage();

        }

    }

}