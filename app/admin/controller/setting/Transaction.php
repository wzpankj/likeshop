<?php
namespace app\admin\controller\setting;

use app\common\basics\AdminBase;
use app\common\server\ConfigServer;
use app\common\server\JsonServer;

/**
 * 交易设置
 */
class Transaction extends AdminBase
{
    public function index()
    {
        $config = [
            'is_show_stock' =>  ConfigServer::get('transaction', 'is_show_stock', 0),
            'dec_stock' =>  ConfigServer::get('transaction', 'dec_stock', 1),
            'return_stock' =>  ConfigServer::get('transaction', 'return_stock', 0),
            'unpaid_order_cancel_time' =>  ConfigServer::get('transaction', 'unpaid_order_cancel_time', 5),
            'paid_order_cancel_time' =>  ConfigServer::get('transaction', 'paid_order_cancel_time', 5),
            'order_auto_receipt_days' =>  ConfigServer::get('transaction', 'order_auto_receipt_days', 1),
        ];
        return view('', [
            'config' => $config
        ]);
    }


    public function set()
    {
        $post = $this->request->post();
        ConfigServer::set('transaction', 'is_show_stock', $post['is_show_stock']); //是否显示库存
        ConfigServer::set('transaction', 'dec_stock', $post['dec_stock']); //库存扣减时机
        ConfigServer::set('transaction', 'return_stock', $post['return_stock']); //取消订单是否退回库存
        ConfigServer::set('transaction', 'unpaid_order_cancel_time', $post['unpaid_order_cancel_time']); //未付款自动取消时长（分钟）
        ConfigServer::set('transaction', 'paid_order_cancel_time', $post['paid_order_cancel_time']); //已支付允许取消时长（分钟）
        ConfigServer::set('transaction', 'order_auto_receipt_days', $post['order_auto_receipt_days']); //已发货订单自动完成时长（天）
        return JsonServer::success('设置成功');
    }
}