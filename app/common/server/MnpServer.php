<?php
// +----------------------------------------------------------------------
// | LikeShop100%开源免费商用电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | Gitee下载：https://gitee.com/likeshop_gitee/likeshop
// | 访问官网：https://www.likemarket.net
// | 访问社区：https://home.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 微信公众号：好象科技
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------

// | Author: LikeShopTeam
// +----------------------------------------------------------------------

namespace app\common\server;

use EasyWeChat\Factory;

class MnpServer
{
    protected $config = null;  // 配置信息
    protected $app = null;     // easyechat实例

    // 初始化
    public function __construct()
    {
        $this->config = WeChatServer::getMnpConfig();
        $this->app = Factory::miniProgram($this->config);
        
    }

    // 生产小程序吗
    public function createQrCode($params=[])
    {
        $response = $this->app->app_code->getUnlimit('shop_id='.$params['shop_id'].'&zhuohao='.$params['number'], [
            'page'  => 'pages/order/order',
            'width' => 600,
        ]);
        
        // 保存小程序码到文件
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $filename = $response->saveAs('uploads/qr_code','shop_id='.$params['shop_id'].'_zhuohao='.$params['number'].'.png');
            return true;
            
        }else{
            return $response['errmsg'];
        }
        
    }


}