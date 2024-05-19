<?php

namespace app\api\controller;
use app\api\
{
    logic\PayLogic,
    validate\UnifiedpayVlidate
};
use app\common\{
    basics\Api,
    model\Client_,
    server\JsonServer,
    server\WeChatServer,
    server\AliPayServer,
    server\WeChatPayServer
};

/**
 * Class Pay
 * @package app\api\controller
 */
class Pay extends Api
{
    public $like_not_need_login = ['notifyMnp', 'notifyOa', 'notifyApp', 'aliNotify'];


    /**
     * @notes 获取支付方式
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author cjhao
     * @date 2021/10/11 9:49
     */
    public function getPayWay()
    {
        $params = $this->request->get();
        if(!isset($params['from']) || !isset($params['order_id'])) {
            return JsonServer::error('参数缺失');
        }
        $pay_way = PayLogic::getPayWay($this->user_id, $this->client, $params);
        return JsonServer::success('', $pay_way);

    }
    /**
     * @notes 支付入口
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author suny
     * @date 2021/7/13 6:13 下午
     */
    public function unifiedpay()
    {
        $post = (new UnifiedpayVlidate())->goCheck();
        $res = PayLogic::unifiedPay($post,$this->client);
        if(false === $res){
            return JsonServer::error(PayLogic::getError());
        }
        return JsonServer::success('',$res);

    }

    /**
     * @notes 小程序回调
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author suny
     * @date 2021/7/13 6:13 下午
     */
    public function notifyMnp()
    {
        $config = WeChatServer::getPayConfig(Client_::mnp);
        return WeChatPayServer::notify($config);
    }


    /**
     * @notes 公众号回调
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author suny
     * @date 2021/7/13 6:13 下午
     */
    public function notifyOa()
    {

        $config = WeChatServer::getPayConfig(Client_::oa);
        return WeChatPayServer::notify($config);
    }


    /**
     * @notes APP回调
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author suny
     * @date 2021/7/13 6:14 下午
     */
    public function notifyApp()
    {

        $config = WeChatServer::getPayConfig(Client_::ios);
        return WeChatPayServer::notify($config);
    }


    /**
     * @notes 支付宝回调
     * @return bool
     * @author suny
     * @date 2021/7/13 6:14 下午
     */
    public function aliNotify()
    {

        $data = $this->request->post();
        return (new AliPayServer())->verifyNotify($data);
    }
}