<?php
// +----------------------------------------------------------------------
// | LikeShop有特色的全开源社交分销电商系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 商业用途务必购买系统授权，以免引起不必要的法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | 微信公众号：好象科技
// | 访问官网：http://www.likemarket.net
// | 访问社区：http://bbs.likemarket.net
// | 访问手册：http://doc.likemarket.net
// | 好象科技开发团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | Author: LikeShopTeam
// +----------------------------------------------------------------------


namespace app\common\server;
use think\facade\Cache;

/**
 * URL转换 服务类
 * Class UrlServer
 * @package app\common\server
 */
class UrlServer
{
    /**
     * @notes 获取储存引擎 TODO 该方法做了缓存处理
     * @return string
     * @author cjhao
     * @date 2021/9/1 11:59
     */
    public static function getStorage():array
    {
        $default = Cache::get('storage_default');
        if(!$default){
            $default = ConfigServer::get('storage', 'default', 'local');
            Cache::tag('storage')->set('storage_default', $default);
        }
        if('local' === $default){

            $domain =  request()->domain();

        }else{

            $domain = Cache::get('storage_engine');
            if (!$domain) {
                $domain = ConfigServer::get('storage_engine', $default)['domain'] ?? '';
                Cache::tag('storage')->set('storage_engine', $domain);
            }
        }

        return ['storage' => $default, 'domain' => $domain];

    }

    /**
     * @notes 获取文件全路径
     * @param string $uri
     * @return string
     * @author cjhao
     * @date 2021/9/1 14:10
     */
    public static function getFileUrl(string $uri):string
    {

        if (strstr($uri, 'http://'))  return $uri;
        if (strstr($uri, 'https://')) return $uri;

        $file_domain = self::getStorage()['domain'];

        return $file_domain . '/' . $uri;
    }


    /**
     * @notes 设置文件路径转相对路径
     * @param string $uri
     * @return string
     * @author cjhao
     * @date 2021/9/1 14:10
     */
    public static function setFileUrl(string $uri):string
    {
        $storage = self::getStorage();
        $engine = $storage['storage'];
        $domain = $storage['domain'];
        if ('local' === $engine) {
            $domain = request()->domain();
            return str_replace($domain.'/', '', $uri);

        } else {

            return str_replace($domain, '', $uri);
        }
    }
}