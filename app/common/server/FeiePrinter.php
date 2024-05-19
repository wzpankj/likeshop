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
namespace app\common\server;
use App\Api\PrinterService;
use App\Api\PrintService;
use app\common\enum\OrderEnum;
use App\Oauth\YlyOauthClient;
use App\Config\YlyConfig;
use Exception;
use think\facade\Cache;
use feiedayin\HttpClient;
use think\facade\Db;
class FeiePrinter{
    /**
     * Notes:打印机接口 店铺里面多台打印机就循环打印
     * @param array $machine_list  打印机信息
     * @param $order 订单信息
     * @return bool|string
     */
    public function ylyPrint($printer_list, $order)
    {
        
        foreach ($printer_list as $printer){
            if(empty($printer['machine_code'])){
                continue;
            }
            $content = "<CB>".$printer['print_number']."</CB>";
            if($printer['printer_template']['title']){
                $content .= "<CB>".$printer['printer_template']['title']."</CB>";
            }
            $content .= "<CB>".$order['shop_name']."</CB>";
            $content .= "<BR>";
            $content .= '订单编号：'.$order['order_sn']."<BR>";
            if ($order['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $content .= '取餐码：'.$order['take_code']."<BR>";
                $content .= '就餐方式：'.$order['dining_type_text']." 桌号：".$order['zhuohao']."<BR>";
                $content .= '取餐时间：'.$order['appoint_time']."<BR>";
            }
            $content .= '下单时间：'.$order['create_time']."<BR>";
            $content .= "<BR>";

            if ($order['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                $content .= "<CB>收货信息</CB>"."<BR>";
                $content .= str_repeat('-', 32)."<BR>";
                $content .= '联系人：'.$order['consignee']."<BR>";
                $content .= '联系方式：'.$order['mobile']."<BR>";
                $content .= '收货地址：'.$order['delivery_address']."<BR>";
                $content .= "<BR>";
                $content .= str_repeat('-', 32)."<BR>";
            }

            if ($order['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $content .= "<CB>联系信息</CB>"."<BR>";
                $content .= str_repeat('-', 32)."<BR>";
                $content .= '联系方式：'.$order['mobile']."<BR>";
                $content .= "<BR>";
                $content .= str_repeat('-', 32)."<BR>";
            }

            $content .= "<CB>商品信息</CB>"."<BR>";
            $content .= str_repeat('-', 32)."<BR>";

            foreach ($order['order_goods'] as $goods){

                $content .= "<BOLD>".$goods['name']."</BOLD>"."<BR>";
                if(isset($goods['goods_snap'])){
                    $materialName = implode(',',$goods['goods_snap']['material_name']);
                    $content .= $goods['spec_value_str'].',' .$materialName.'  '.'￥'.$goods['goods_price'].'   '.'x'.$goods['goods_num']."<BR>";
                }
                $content .= "<BR>";
            }

            $content .= str_repeat('-', 32)."<BR>";

            $content .= '商品金额：￥'.$order['goods_price']."<BR>";
            $content .= '优惠金额：￥'.$order['discount_amount']."<BR>";
            $content .= '运费金额：￥'.$order['delivery_amount']."<BR>";
            $content .= '应付金额：￥'.$order['order_amount']."<BR>";
            $content .= "<BR>";
            if($order['user_remark']){
                $content .= "订单备注：<FS>".$order['user_remark']."</FS>"."<BR>";
            }

            $content .= "<BR>";
            //二维码
            if($printer['printer_template']['is_qrCode'] == 1){
                $content .= "<QR>".$printer['printer_template']['qrCode_link']."</QR>"."<BR>";
            }
            if($printer['printer_template']['bottom']){
                $content .=  "<center>".$printer['printer_template']['bottom']."</center>"."<BR>";
            }
            
            define('SN', $printer['machine_code']);      //*必填*：打印机编号，必须要在管理后台里添加打印机或调用API接口添加之后，才能调用API


            $time = time();         //请求时间
			// DB::name('debug_log')->insert(['spec' => "114".serialize(json_encode($printer))]);
			// DB::name('debug_log')->insert(['spec'=> "115".serialize(json_encode(USER))]);
            $msgInfo = array(
              'user'=>USER,
              'stime'=>$time,
              'sig'=>sha1(USER.UKEY.$time),
              'apiname'=>'Open_printMsg',
              'sn'=>$printer['machine_code'],
              'content'=>$content,
              'times'=>1//打印次数
            );
            $client = new HttpClient(IP,PORT);
            if(!$client->post(PATH,$msgInfo)){
              // echo 'error';
            }else{
              //服务器返回的JSON字符串，建议要当做日志记录起来
              $result = $client->getContent();
              // return $result;
            }

            // var_dump($content);
            // exit;
            // $print->index($printer['machine_code'], $content, $order['order_sn']);
        }

    }

}

