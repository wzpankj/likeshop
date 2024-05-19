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

class YlyPrinter{
    private $client_id = '';
    private $client_secret = '';
    private $yly_config = '';
    protected $access_token = '';


    public function __construct($client_id = '',$client_secret = ''){

        $this->client_id = $client_id;                 //应用id
        $this->client_secret = $client_secret;         // 应用密钥

        $this->yly_config = new YlyConfig($this->client_id, $this->client_secret);

        $this->access_token = Cache::get('yly_access_token');
        //没有access_token时获取
        if(empty($this->access_token)){
            $this->getToken();
        }
    }

    /**
     * Notes:获取access_token
     * @return mixed
     */
    public function getToken(){
        $client = new YlyOauthClient($this->yly_config);
        $token = $client->getToken();


        $this->access_token = $token->access_token;
        Cache::tag('yly_printer')->set('yly_access_token',$this->access_token);
        //刷新token、有效期35天(自用型刷新token作用不大)
        Cache::tag('yly_printer')->set('yly_refresh_token',$token->refresh_token,35*24*3600);

    }

    /**
     * Notes:刷新access_token
     * @return mixed
     */
    public function refreshToken(){

        $client = new YlyOauthClient($this->yly_config);
        $token = $client->refreshToken(Cache::get('yly_refresh_token'));

        $this->access_token = $token->access_token;
        //重置token
        Cache::tag('yly_printer')->set('yly_access_token',$this->access_token);
        Cache::tag('yly_printer')->set('yly_refresh_token',$token->refresh_token,35*24*3600);

    }

    /**
     * Notes: 添加打印机
     * @param string $machine_code 终端号
     * @param string $msign 秘钥
     * @param string $print_name 打印机名称
     * @return bool|string
     */
    public function addPrinter($machine_code,$msign,$print_name){


        $print = new PrinterService($this->access_token,$this->yly_config);
        $response = $print->addPrinter($machine_code,$msign,$print_name);
        return $response;
    }

    /**
     * Notes:删除打印机
     * @param sting $machine_code 终端号
     * @return bool|string
     */
    public function deletePrinter($machine_code){

        $print = new PrinterService($this->access_token,$this->yly_config);
        $print->deletePrinter($machine_code);

    }

    /**
     * Notes: 设置logo
     * @param sting $machine_code 终端号
     * @param sting $url logo
     */
    public function setIcon($machine_code,$url){
        $print = new PrinterService($this->access_token,$this->yly_config);
        $print->setIcon($machine_code,$url);
    }

    /**
     * Notes:获取终端状态
     * @param sting $machine_code 终端号
     */
    public function getPrintStatus($machine_code){
        $print = new PrinterService($this->access_token,$this->yly_config);
        $response = $print->getPrintStatus($machine_code);
        return $response;
    }

    /**
     * Notes:打印机接口
     * @param array $machine_list  打印机信息
     * @param $order 订单信息
     * @return bool|string
     */
    public function ylyPrint($printer_list, $order)
    {
        $print = new PrintService($this->access_token, $this->yly_config);

        foreach ($printer_list as $printer){
            if(empty($printer['machine_code'])){
                continue;
            }
            $content = "<MN>".$printer['print_number']."</MN>";
            if($printer['printer_template']['title']){
                $content .= "<FS2><center>".$printer['printer_template']['title']."</center></FS2>";
            }
            $content .= "<FS2><center>".$order['shop_name']."</center></FS2>";
            $content .= PHP_EOL;
            $content .= '订单编号：'.$order['order_sn'].PHP_EOL;
            if ($order['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $content .= '取餐码：'.$order['take_code'].PHP_EOL;
                $content .= '就餐方式：'.$order['dining_type_text'].PHP_EOL;
                $content .= '取餐时间：'.$order['appoint_time'].PHP_EOL;
            }
            $content .= '下单时间：'.$order['create_time'].PHP_EOL;
            $content .= PHP_EOL;

            if ($order['order_type'] == OrderEnum::ORDER_TYPE_TAKE_AWAY) {
                $content .= "<FS2>收货信息</FS2>".PHP_EOL;
                $content .= str_repeat('-', 32).PHP_EOL;
                $content .= '联系人：'.$order['consignee'].PHP_EOL;
                $content .= '联系方式：'.$order['mobile'].PHP_EOL;
                $content .= '收货地址：'.$order['delivery_address'].PHP_EOL;
                $content .= PHP_EOL;
                $content .= str_repeat('-', 32).PHP_EOL;
            }

            if ($order['order_type'] == OrderEnum::ORDER_TYPE_YOUSELF_TAKE) {
                $content .= "<FS2>联系信息</FS2>".PHP_EOL;
                $content .= str_repeat('-', 32).PHP_EOL;
                $content .= '联系方式：'.$order['mobile'].PHP_EOL;
                $content .= PHP_EOL;
                $content .= str_repeat('-', 32).PHP_EOL;
            }

            $content .= "<FS2>商品信息</FS2>".PHP_EOL;
            $content .= str_repeat('-', 32).PHP_EOL;

            foreach ($order['order_goods'] as $goods){

                $content .= $goods['name'].PHP_EOL;
                if(isset($goods['goods_snap'])){
                    $materialName = implode(',',$goods['goods_snap']['material_name']);
                    $content .= $goods['spec_value_str'].',' .$materialName.'  '.'￥'.$goods['goods_price'].'   '.'x'.$goods['goods_num'].PHP_EOL;
                }
                $content .= PHP_EOL;
            }

            $content .= str_repeat('-', 32).PHP_EOL;

            $content .= '商品金额：￥'.$order['goods_price'].PHP_EOL;
            $content .= '优惠金额：￥'.$order['discount_amount'].PHP_EOL;
            $content .= '运费金额：￥'.$order['delivery_amount'].PHP_EOL;
            $content .= '应付金额：￥'.$order['order_amount'].PHP_EOL;
            $content .= PHP_EOL;
            if($order['user_remark']){
                $content .= "订单备注：<FS>".$order['user_remark']."</FS>".PHP_EOL;
            }

            $content .= PHP_EOL;
            //二维码
            if($printer['printer_template']['is_qrCode'] == 1){
                $content .= "<QR>".$printer['printer_template']['qrCode_link']."</QR>".PHP_EOL;
            }
            if($printer['printer_template']['bottom']){
                $content .=  "<center>".$printer['printer_template']['bottom']."</center>".PHP_EOL;
            }
            $print->index($printer['machine_code'], $content, $order['order_sn']);
        }

    }

}

