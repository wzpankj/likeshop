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

namespace app\shop\validate\order;


use app\common\basics\Validate;
use app\common\enum\OrderEnum;
use app\common\enum\PayEnum;
use app\common\model\order\Order;
use app\common\model\Printer;
use app\common\model\PrinterConfig;
use think\facade\Db;

/**
 * 到店订单验证器
 * Class ShopOrderValidate
 * @package app\shop\validate\order
 */
class ShopOrderValidate extends Validate
{
    protected $rule = [
        'id' => 'require|checkId',
        'order_ids' => 'require|array',
        'refund_type' => 'require|number',
        'refund_way' => 'require|number',
        'refund_amount' => 'require|float|checkRefundAmount',
        'refund_remark' => 'require',
    ];

    public function sceneCencel()
    {
        return $this->only(['order_ids'])
            ->append('order_ids','checkOrdeIds');
    }

    public function sceneNotice()
    {
        return $this->only(['order_ids'])
            ->append('order_ids','checkNotice');
    }

    public function sceneConfirm()
    {
        return $this->only(['order_ids'])
            ->append('order_ids','checkConfirm');
    }

    public function sceneRemarks()
    {
        return $this->only(['id']);
    }

    public function scenePrint()
    {
        return $this->only(['id'])
            ->append('id','checkPrint');
    }

    public function sceneRefund()
    {
        return $this->only(['id','refund_type','refund_way','refund_amount','refund_remark'])
            ->append('id','checkRefund');
    }

    /**
     * @notes 检验订单是否能取消
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/16 3:47 下午
     */
    public function checkOrdeIds($value,$rule,$data)
    {
        foreach ($value as $val) {
            $result = Order::where(['id'=>$val,'del'=>0])->findOrEmpty();
            if ($result->isEmpty()) {
                return '存在非法订单，无法取消';
            }
            if ($result['order_status'] > OrderEnum::ORDER_STATUS_GOODS) {
                return '订单'.$result['id'].'无法取消';
            }
        }
        return true;
    }

    /**
     * @notes 检验订单是否可以取餐
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/16 6:41 下午
     */
    public function checkConfirm($value,$rule,$data)
    {
        foreach ($value as $val) {
            $result = Order::where(['id'=>$val,'del'=>0])->findOrEmpty();
            if ($result->isEmpty()) {
                return '存在非法订单，无法确认取餐';
            }
            if ($result['order_status'] != OrderEnum::ORDER_STATUS_GOODS) {
                return '订单'.$result['id'].'无法确认取餐';
            }
        }
        return true;
    }

    /**
     * @notes 检验订单是否可以通知取餐
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/16 7:00 下午
     */
    public function checkNotice($value,$rule,$data)
    {
        foreach ($value as $val) {
            $result = Order::where(['id'=>$val,'del'=>0])->findOrEmpty();
            if ($result->isEmpty()) {
                return '存在非法订单，无法通知取餐';
            }
            if ($result['order_status'] != OrderEnum::ORDER_STATUS_DELIVERY) {
                return '订单'.$result['id'].'无法通知取餐';
            }
        }
        return true;
    }

    /**
     * @notes 检验订单是否存在
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/17 10:18 上午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Order::where(['id'=>$value,'del'=>0])->findOrEmpty();
        if ($result->isEmpty()) {
            return '订单不存在';
        }

        return true;
    }

    /**
     * @notes 小票打印验证
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/17 10:25 上午
     */
    public function checkPrint($value,$rule,$data)
    {
        $printer_config = PrinterConfig::where(1,1)->findOrEmpty();

        if ($printer_config->isEmpty()) {
            return '请先到小票打印里面配置打印设置';
        }

        $printer = Printer::where(['printer_config_id' => $printer_config['id']])->findOrEmpty();
        if ($printer->isEmpty()) {
            return '请先添加打印机';
        }
        if ($printer['status'] != 1) {
            return '打印机打印状态未开启';
        }
        return true;
    }


    /**
     * @notes 检验订单是否可以发起退款
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/18 3:27 下午
     */
    public function checkRefund($value,$rule,$data)
    {
        $result = Order::where(['id'=>$value])->findOrEmpty();
        if ($result->isEmpty()) {
            return '订单不存在';
        }
        if ($result['pay_status'] != PayEnum::ISPAID) {
            return '订单未支付，不能发起退款';
        }
        if ($result['order_status'] != OrderEnum::ORDER_STATUS_COMPLETE && $result['order_status'] != OrderEnum::ORDER_STATUS_DOWN) {
            return '已完成或已关闭的订单才可以发起订单退款';
        }
        if ($result['refund_amount'] != null && $result['refund_amount'] == $result['order_amount']) {
            return '订单金额已全部退还，无法发起退款';
        }

        return true;
    }

    /**
     * @notes 检验退款金额是否合理
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/9/18 6:35 下午
     */
    public function checkRefundAmount($value,$rule,$data)
    {
        $result = Order::where(['id'=>$data['id']])->findOrEmpty();
        if ($value < 0) {
            return '退款金额小于零，无法退款';
        }
        if ($value > (($result['surplus_refund_amount'] == null) ? $result['order_amount'] : $result['surplus_refund_amount'])) {
            return '退款金额大于剩余可退金额，无法退款';
        }
        if ($result['surplus_refund_amount'] != null && $result['surplus_refund_amount'] == 0) {
            return '剩余可退款金额为零，无法退款';
        }

        return true;
    }
}