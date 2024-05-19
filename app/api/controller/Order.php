<?php

namespace app\api\controller;
use think\facade\Db;
use app\api\{
    logic\OrderLogic,
    validate\OrderValidate
};
use app\common\{
    basics\Api,
    server\JsonServer
};

/**
 * 订单类
 * Class Order
 * @package app\api\controller
 */
class Order extends Api
{
    /**
     * @notes 提交订单
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/9/6 10:12
     */
    public function sumbitOrder()
    {

        $post = $this->request->post();
        $post['user_id'] = $this->user_id;
        $post['client'] = $this->client;
        //test
		//DB::name('debug_log')->insert(['spec'=> "37".serialize(json_encode($post))]);
        (new OrderValidate())->goCheck('sumbitOrder',$post);
        $order = OrderLogic::sumbitOrder($post);
        if (false === $order) {
            return JsonServer::error(OrderLogic::getError());
        }
        return JsonServer::success('', $order);
    }

    /**
     * @notes 订单详情
     * @return \think\response\Json
     * @author cjhao
     * @date 2021/9/16 15:31
     */
    public function getOrderDetail()
    {

        $id = $this->request->get('id/d');
        $order_detail = OrderLogic::getOrderDetail($id,$this->user_id);
        return JsonServer::success('获取成功', $order_detail);
    }

    /**
     * @notes 取消订单
     * @return bool|string
     * @author cjhao
     * @date 2021/9/22 9:38
     */
    public function cancel()
    {

        $order_id = $this->request->post('id');
        $res = OrderLogic::cancel($order_id, $this->user_id);
        if(true === $res){
            return JsonServer::success('取消成功');
        }
        return JsonServer::error( $res);
    }


    /**
     * @notes 订单列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/9/24 5:11 下午
     */
    public function lists()
    {
        $type = $this->request->get('type', 'all');
        $order_list = OrderLogic::lists($this->user_id, $type, $this->page_no, $this->page_size);
        return JsonServer::success('获取成功', $order_list);
    }


    /**
     * @notes 再来一单
     * @return \think\response\Json
     * @author ljj
     * @date 2021/9/26 2:36 下午
     */
    public function again()
    {
        $post = $this->request->post();
        $result = OrderLogic::again($post, $this->user_id);
        if (true !== $result) {
            return JsonServer::error(OrderLogic::getError());
        }
        return JsonServer::success('操作成功');
    }


}
