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

namespace app\shop\validate\printer;

use app\common\model\Printer;
use app\common\model\PrinterConfig;
use app\common\model\PrinterTemplate;
use think\Db;
use app\common\basics\Validate;

/**
 * 打印机管理验证器
 * Class PrinterValidate
 * @package app\shop\validate\printer
 */
class PrinterValidate extends Validate
{
    protected $rule = [
        'id'                    => 'require|checkId',
        'printer_config_id'     => 'require|checkConfig',
        'template_id'           => 'require|checkTemplate',
        'name'                  => 'require|checkName',
        'machine_code'          => 'require|checkCode',
        'private_key'           => 'require|checkKey',
        'print_number'          => 'require|number',
        'status'                => 'require|in:0,1',
        'auto_print'            => 'require|in:0,1',
    ];

    protected $message = [
        'id.require'            => '请选择打印机',
        'printer_config_id.require'          => '请选择打印机类型',
        'name.require'          => '请输入打印机名称',
        'machine_code.require'  => '请输入终端号',
        'private_key.require'   => '请输入秘钥',
        'print_number.require'  => '请输入打印联数',

    ];

    public function sceneAdd()
    {
        return $this->only(['printer_config_id','name','machine_code','private_key','print_number','status','auto_print','template_id']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','printer_config_id','name','machine_code','private_key','print_number','status','auto_print','template_id']);
    }

    public function sceneDel()
    {
        return $this->only(['id']);
    }

    public function sceneTestPrint()
    {
        return $this->only(['id'])
            ->append('id','checkPrinter');
    }

    /**
     * @notes 检验打印机是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 2:48 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Printer::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '打印机不存在';
        }

        return true;
    }

    /**
     * @notes 检验打印机配置
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 2:51 下午
     */
    protected function checkConfig($value,$rule,$data)
    {
        $result = PrinterConfig::where(['id'=>$value])->findOrEmpty();
        if ($result->isEmpty()) {
            return '打印机配置错误';
        }
        if(!$result['client_id'] || !$result['client_secret']){
            return '请先设置'.$result['name'].'的配置';
        }

        return true;
    }

    /**
     * @notes 检验打印机名称是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 2:54 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['name', '=', $value];
        $where[] = ['shop_id', '=', $data['shop_id']];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = Printer::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '打印机名称已存在';
        }

        return true;
    }

    /**
     * @notes 检验终端号
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 2:56 下午
     */
    public function checkCode($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['machine_code', '=', $value];
        $where[] = ['shop_id', '=', $data['shop_id']];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = Printer::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '终端号已存在';
        }

        return true;
    }

    /**
     * @notes 检验秘钥
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 3:01 下午
     */
    public function checkKey($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['private_key', '=', $value];
        $where[] = ['shop_id', '=', $data['shop_id']];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = Printer::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '秘钥已存在';
        }

        return true;
    }

    /**
     * @notes 检验打印机配置
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/23 6:00 下午
     */
    protected function checkPrinter($value,$rule,$data)
    {
        $result = Printer::where(['id'=>$value])->findOrEmpty();
        if(!$result['machine_code'] || !$result['private_key']){
            return '打印机配置错误';
        }
        if ($result['status'] == 0) {
            return '打印状态未开启';
        }

        $type = PrinterConfig::where(['id'=>$result['printer_config_id']])->findOrEmpty();
        if($type->isEmpty()){
            return '打印配置错误';
        }
        if(!$type['client_id'] || !$type['client_secret']){
            return '请先设置'.$type['name'].'的配置';
        }

        return true;
    }

    /**
     * @notes 检验小票模板
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/28 3:57 下午
     */
    public function checkTemplate($value,$rule,$data)
    {
        $result = PrinterTemplate::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '小票模板不存在';
        }

        return true;
    }
}