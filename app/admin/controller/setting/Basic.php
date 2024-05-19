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

namespace app\admin\controller\setting;

use app\common\server\ConfigServer;
use app\common\server\UrlServer;
use app\common\basics\AdminBase;
use app\admin\logic\setting\BasicLogic;
use app\common\server\JsonServer;

/**
 * 基础设置
 * Class Basic
 * @package app\admin\controller\setting
 */
class Basic extends AdminBase
{

    /**
     * 网站配置
     * @return mixed
     */
    public function website()
    {
        return view('', [
            'config' => BasicLogic::getBasicConfig()
        ]);
    }

    /**
     * Notes: 网站设置
     * @author 段誉(2021/6/10 20:32)
     * @return \think\response\Json
     */
    public function setWebsite()
    {
        $post = $this->request->post();
        if (empty($post['type'])) {
            return JsonServer::error('参数缺失');
        }
        if ($post['type'] == 'base') {
            BasicLogic::setWebsiteBasic($post);
        } elseif ($post['type'] == 'platform') {
            BasicLogic::setPlatform($post);
        } elseif ($post['type'] == 'shop') {
            BasicLogic::setShop($post);
        }
        return JsonServer::success('修改成功');
    }


    /**
     * Notes: 版权备案
     * @author 段誉(2021/6/10 23:55)
     * @return \think\response\View
     */
    public function copyright()
    {
        $config = [
            'company_name' => ConfigServer::get('copyright', 'company_name'),
            'number' => ConfigServer::get('copyright', 'number'),
            'link' => ConfigServer::get('copyright', 'link'),
        ];
        return view('', ['config' => $config]);
    }


    /**
     * Notes: 设置版权备案
     * @author 段誉(2021/6/10 23:55)
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setCopyright()
    {
        $post = $this->request->post();
        ConfigServer::set('copyright', 'company_name', $post['company_name']);
        ConfigServer::set('copyright', 'number', $post['number']);
        ConfigServer::set('copyright', 'link', $post['link']);
        return JsonServer::success('修改成功');
    }


    /**
     * Notes: APP设置
     * @author 段誉(2021/6/11 1:00)
     * @return \think\response\View
     */
    public function app()
    {
        $config = [
            'line_ios' => ConfigServer::get('app', 'line_ios', ''),
            'line_android' => ConfigServer::get('app', 'line_android', ''),
            'download_doc' => ConfigServer::get('app', 'download_doc', ''),
            'agreement' => ConfigServer::get('app', 'agreement', 0),
            'wechat_login'  => ConfigServer::get('app', 'wechat_login',  0),
        ];
        return view('', ['config' => $config]);
    }

    public function setApp()
    {
        $post = $this->request->post();
        $post['agreement'] = isset($post['agreement']) && $post['agreement'] == 'on' ? 1 : 0;
        $post['wechat_login'] = isset($post['wechat_login']) && $post['wechat_login'] == 'on' ? 1 : 0;
        ConfigServer::set('app', 'line_ios',$post['line_ios']);
        ConfigServer::set('app', 'line_android',$post['line_android']);
        ConfigServer::set('app', 'download_doc',$post['download_doc']);
        ConfigServer::set('app', 'agreement',$post['agreement']);
        ConfigServer::set('app', 'wechat_login',$post['wechat_login']);
        return JsonServer::success('修改成功');
    }

    /**
     * 分享设置
     */
    public function share()
    {
        $config = [
            'mnp' => ConfigServer::get('share', 'mnp', [
                'mnp_share_title' => ''
            ])
        ];
        return view('', ['config' => $config]);
    }

    public function setShare()
    {
        $post = $this->request->post();
        $mnp = json_encode([
            'mnp_share_title' => $post['mnp_share_title'],
        ], JSON_UNESCAPED_UNICODE);
        ConfigServer::set('share', 'mnp', $mnp);
        return JsonServer::success('修改成功');
    }



}