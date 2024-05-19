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

namespace app\admin\validate\printer;

use app\common\model\Printer;
use app\common\model\PrinterTemplate;
use app\common\basics\Validate;

/**
 * 小票模板验证器
 * Class TemplateValidate
 * @package app\admin\validate\printer
 */
class TemplateValidate extends Validate
{
    protected $rule = [
        'id'                    => 'require|checkId',
        'name'                  => 'require|checkName',
        'title'                 => 'require',
        'is_qrCode'             => 'require|in:0,1',
    ];

    protected $message = [
        'id.require'            => '请选择小票模板',
        'name.require'          => '请输入模板名称',
        'title.require'         => '请输入小票标题',

    ];

    public function sceneAdd()
    {
        return $this->only(['name','title','is_qrCode']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','title','is_qrCode']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }

    /**
     * @notes 检验小票模板是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/29 11:14 上午
     */
    public function checkId($value,$rule,$data)
    {
        $result = PrinterTemplate::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '小票模板不存在';
        }

        return true;
    }

    /**
     * @notes 检验模板名称是否已存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/29 11:15 上午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['name', '=', $value];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = PrinterTemplate::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '模板名称已存在';
        }

        return true;
    }

    /**
     * @notes 检验小票模板能否删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/29 3:36 下午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = Printer::where(['printer_config_id'=>$value,'del'=>0])->findOrEmpty();
        if (!$result->isEmpty()) {
            return '小票模板使用中，不能删除';
        }

        return true;
    }
}