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

namespace app\shop\logic\zhuohao;

use app\common\basics\Logic;
use app\common\model\Zhuohao;
use think\Exception;
use think\facade\Cache;
use think\facade\Db;
use app\common\server\MnpServer;

/**
 * 打印机管理逻辑层
 * Class PrinterLogic
 * @package app\shop\logic\printer
 */
class ZhuohaoLogic extends Logic
{
    /**
     * @notes 打印机列表
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 11:54 上午
     */
    public static function lists($admin_id)
    {
        $lists = Zhuohao::alias('p')
            ->where(['p.del'=>0,'p.shop_id'=>$admin_id])
            ->order('p.id desc')
            ->select()
            ->toArray();

        return ['count' => 0,'lists' => $lists];
    }

    /**
     * @notes 获取打印机配置列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 2:28 下午
     */
    public static function getConfigList($admin_id){
        return PrinterConfig::where(1,1)->field('id,name')->select()->toArray();
    }

    /**
     * @notes 获取小票模板列表
     * @param $shop_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/28 3:34 下午
     */
    public static function getTemplateList($admin_id){
        return PrinterTemplate::where('del',0)->field('id,name')->select()->toArray();
    }

    /**
     * @notes 添加打印机
     * @param $post
     * @param $admin_id
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 5:22 下午
     */
    public static function add($post,$admin_id)
    {
        Db::startTrans();
        try {
            //添加打印机数据
            $printer = Zhuohao::create([
                'shop_id'                       => $admin_id,
                'name'                          => $post['name'],
                'number'                        => $post['number'],
                'create_time'                   => time(),
                'update_time'                   => time(),
                'code'                          =>'uploads/qr_code/'.'shop_id='.$admin_id.'_zhuohao='.$post['number'].'.png'
            ]);
            
            #调用生成小程序码
            $nimei=new MnpServer();
            $res=$nimei->createQrCode(['shop_id'=>$admin_id,'number'=>$post['number']]);
            if ($res===TRUE) {
                Db::commit();
                return true;
            }
            Db::rollback();
            // throw new Exception($res);
            return '添加失败：'.$res;
        }catch (\Exception $e){

            
            Db::rollback();
            return false;
        }
    }

    /**
     * @notes 编辑打印机
     * @param $post
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 5:40 下午
     */
    public static function edit($post)
    {
        
        try {
            //添加打印机数据
            $printer = Zhuohao::update([
                'name'             => $post['name'],
                'update_time'                   => time(),
            ],['id'=>$post['id']]);
            return true;

        }catch (\Exception $e){

           return false;
        }
    }

    /**
     * @notes 打印机详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/23 5:42 下午
     */
    public static function getDetail($id)
    {
        return Zhuohao::where(['del'=>0,'id'=>$id])->find()->toArray();
    }

    /**
     * @notes 删除打印机
     * @param $id
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 5:51 下午
     */
    public static function del($id)
    {
       
        try {
            //删除打印机
            $printer = Zhuohao::find($id);
            $printer->del = 1;
            $printer->update_time = time();
            $printer->save();
            return true;

        }catch (\Exception $e){

            
            return false;
        }
    }

    /**
     * @notes 测试打印
     * @param $id
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 6:04 下午
     */
    public static function testPrint($id)
    {
        try {
            $printer = Printer::find($id)->toArray();

            $printer_config = PrinterConfig::where(['id'=>$printer['printer_config_id']])->field('client_id,client_secret')->find();

            $printer_template = PrinterTemplate::find($printer['template_id'])->toArray();

            $yly_print = new YlyPrinter($printer_config['client_id'],$printer_config['client_secret']);

            //获取打印机状态
            $response = $yly_print->getPrintStatus($printer['machine_code']);

            if(1 == $response->body->state){
                $data = static::testData();
                $yly_print->ylyPrint([['machine_code'=>$printer['machine_code'],'print_number'=>$printer['print_number'],'printer_template'=>$printer_template]],$data);
                return true;
            }
            $msg = '打印机离线';

            if(2 == $response->body->state){
                $msg = '打印机缺纸';
            }
            throw new Exception($msg);
        }catch (\Exception $e){

            $msg = json_decode($e->getMessage(),true);
            if($msg && isset($msg['error'])){
                return '易联云：'.$msg['error_description'];
            }
            if(18 === $e->getCode()){
                //todo token过期重新拿
                Cache::tag('yly_printer')->clear();
            };
            return '易联云：'.$e->getMessage();
        }
    }

    /**
     * @notes 测试数据
     * @return array
     * @author ljj
     * @date 2021/9/23 6:04 下午
     */
    public static function testData()
    {
        $order = [
            'order_type'        => 2,
            'shop_name'         => '一点点',
            'take_code'         => '123456',
            'dining_type_text'       => '店内就餐',
            'appoint_time'      => '立即取餐',
            'order_sn'          => date("Ymd").'88888888888',
            'create_time'       => date('Y-m-d H:i:s'),
            'consignee'         => '张先生',
            'mobile'            => '138888888888',
            'delivery_address'  => '广东省广州市天河区XXXX科技园',
            'user_remark'       => '这是用户备注',
            'order_goods'       => [
                [
                    'name'          => 'iPhone 11',
                    'spec_value_str'=> '全网通256G，银色',
                    'goods_num'     => '88',
                    'goods_price'   => '3689',
                    'total_price'   => '88888',
                ],
                [
                    'name'          => '小米手机Plus',
                    'spec_value_str'=> '全网通256G，黑色',
                    'goods_num'     => '88',
                    'goods_price'   => '3689',
                    'total_price'   => '88888',
                ],
                [
                    'name'          => '华为 P40',
                    'spec_value_str'=> '全网通256G，黑色',
                    'goods_num'     => '88',
                    'goods_price'   => '3689',
                    'total_price'   => '88888',
                ],
            ],
            'goods_price'       => '888888',  //商品总价
            'discount_amount'   => '80',      //优惠金额
            'delivery_amount'   => '12',      //配送费用
            'order_amount'      => '222'      //应付金额
        ];
        return $order;
    }
}