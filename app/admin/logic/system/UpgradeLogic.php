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
// | Author: LikeShopTeam-段誉
// +----------------------------------------------------------------------


namespace app\admin\logic\system;


use app\common\basics\Logic;
use think\facade\Cache;
use think\facade\Db;
use Requests;
use think\Exception;
use think\facade\Log;

/**
 * 更新逻辑
 * Class UpgradeLogic
 * @package app\admin\logic\system
 */
class UpgradeLogic extends Logic
{

    protected static $base_url = 'https://server.likeshop.cn';


    /**
     * Notes: 版本列表
     * @param $page_no
     * @param $page_size
     * @author 段誉(2021/7/9 0:55)
     * @return array
     */
    public static function index($page_no, $page_size)
    {
        $lists = self::getRemoteVersion($page_no, $page_size) ?? [];
        if (empty($lists)) {
            return ['count' => 0, 'lists' => []];
        }

        $local_data = local_version();
        $local_version = $local_data['version'];

        foreach ($lists as $k => $item) {
            //版本描述
            $lists[$k]['version_str'] = '';
            $lists[$k]['able_update'] = 0;
            if ($local_version == $item['version_no']) {
                $lists[$k]['version_str'] = '您的系统当前处于此版本';
            }
            if ($local_version < $item['version_no']) {
                $lists[$k]['version_str'] = '系统可更新至此版本';
                $lists[$k]['able_update'] = 1;
            }

            //最新的版本号标志
            $lists[$k]['new_version'] = 0;
            $lists[0]['new_version'] = 1;

            //注意,是否需要重新发布描述
            $lists[$k]['notice'] = '';
            if ($item['uniapp_publish'] == 1) {
                $lists[$k]['notice'] .= '更新至当前版本后需重新发布前端商城'.'<br>';
            }
            if ($item['pc_admin_publish'] == 1) {
                $lists[$k]['notice'] .= '更新至当前版本后需重新发布PC管理端'.'<br>';
            }
            if ($item['pc_shop_publish'] == 1) {
                $lists[$k]['notice'] .= '更新至当前版本后需重新发布PC商城端';
            }

            //处理更新内容信息
            $contents = $item['update_content'];
            $add = [];
            $optimize = [];
            $repair = [];
            if (!empty($contents)) {
                foreach ($contents as $content) {
                    if ($content['type'] == 1) {
                        $add[] = '新增:'.$content['update_function'];
                    }
                    if ($content['type'] == 2) {
                        $optimize[] = '优化:'.$content['update_function'];
                    }
                    if ($content['type'] == 3) {
                        $repair[] = '修复:'.$content['update_function'];
                    }
                }
            }
            $lists[$k]['add'] = $add;
            $lists[$k]['optimize'] = $optimize;
            $lists[$k]['repair'] = $repair;
            unset($lists[$k]['update_content']);
        }

        return ['count' => count($lists), 'lists' => $lists];
    }



    /**
     * Notes: 获取远程数据
     * @param string $page_no
     * @param string $page_size
     * @author 段誉(2021/7/9 0:54)
     * @return mixed
     */
    public static function getRemoteVersion($page_no = '', $page_size = '')
    {
        $cache_version = Cache::get('version_lists');
        if (!empty($cache_version)) {
            return $cache_version;
        }
        if (empty($page_no) || empty($page_size)) {
            $remote_url =  self::$base_url."/api/version/lists?product_id=4&type=2";
        } else {
            $remote_url = self::$base_url."/api/version/lists?product_id=4&type=2&page_no=$page_no&page_size=$page_size";
        }
        $result = Requests::get($remote_url);
        $result = json_decode($result->body, true);
        $result = $result['data'] ?? [];
        Cache::set('version_lists', $result, 1800);
        return $result;
    }




    /**
     * Notes: 更新主程序
     * @param $version
     * @return bool|string
     * @author 段誉(2021/7/7 18:34)
     */
    public static function upgrade($post)
    {
        $post['update_type'] = 1;
        // 授权验证
        $post['link'] = "package_link";

//        $open_basedir = ini_get('open_basedir');
//        if(strpos($open_basedir, 'server') !== false) {
//            return '请参考技术社区对应产品升级文章配置open_basedir';
//        }
        //要更新的版本
        $version = $post['version_no'];
        // 本地更新路径
        $local_upgrade_dir = ROOT_PATH . '/upgrade/';
        // 本地更新包路径
        $path = ROOT_PATH . '/upgrade/';
        // 本地更新临时文件
        $temp_dir = $path . 'temp/';

        Db::startTrans();
        try {

            $result = self::verify($post);
            if (!$result['has_permission']) {
                // 写日志
//            self::addlog($post,false);
                throw new Exception('请先联系客服获取授权');
            }

            //远程下载链接
            $remote_url = $result['link'];

            if (!is_dir($path)) {
                mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
            }
            //下载更新压缩包保存到本地
            $remote_data = self::downFile($remote_url, $local_upgrade_dir);
            if (false === $remote_data) {
                throw new Exception('获取文件错误');
            }
            //解压缩
            if (false === unzip($remote_data['save_path'], $temp_dir)) {
                throw new Exception('解压文件错误');
            }
            //更新sql
            if (false === self::upgradeSql($temp_dir . 'sql/data/')) {
                throw new Exception('更新数据库失败');
            }
            //更新文件
            if (false === self::upgradeDir($temp_dir . 'server/', self::getProjectPath())) {
                throw new Exception('更新文件失败');
            }
            //更新本地版本号文件
            $upgrade_data = ['version' => $version];
            if (false === self::upgradeVersion($upgrade_data)) {
                throw new Exception('本地更新日志写入失败');
            }
            //删除临时文件(压缩包不删除,删除解压的文件)
            if (false === del_target_dir($temp_dir, true)) {
                Log::write('删除临时文件失败');
            }

            Db::commit();
            //增加日志
            self::addlog($post,true);
            return true;

        } catch (Exception $e) {

            Db::rollback();
            //错误日志
            $post['error'] = $e->getMessage();
            self::addlog($post, false);
            //删除临时文件(压缩包不删除,删除解压的文件)
            if (false === del_target_dir($temp_dir, true)) {
                Log::write('删除临时文件失败');
            }
            return $e->getMessage();
        }
    }



    /**
     * Notes: 添加日志
     * @param $data
     * @param bool $status
     * @author 段誉(2021/7/12 14:56)
     * @return bool|\Requests_Response
     */
    public static function addlog($data, $status = true)
    {
        $log_msg = json_encode($data, JSON_UNESCAPED_UNICODE);
        Log::write('更新日志:'.$log_msg);

        try{
            $post_data = [
                'version_id' => $data['id'],
                'product_id' => 4,
                'version_no' => $data['version_no'],
                'ip_address' => get_client_ip(),
                'domain' => request()->domain(),
                'type' => 2,
                'update_type' => $data['update_type'],
                'status' => $status ? 1 : 0,
                'error' => $data['error'] ?? ''
            ];
            $request_url = self::$base_url.'/api/version/log';
            return Requests::post($request_url, [], $post_data);
        } catch(\Exception $e) {

            Log::write('更新日志添加失败:'.$e->getMessage());
            return false;
        }
    }


    /**
     * Notes: 下载压缩包
     * @param $url
     * @param string $savePath
     * @author 段誉(2021/7/12 14:56)
     * @return array|bool
     */
    public static function downFile($url, $savePath = './upgrade/')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $header = '';
        $body = '';
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        }
        curl_close($ch);
        //文件名
        $fullName = basename($url);
        //文件保存完整路径
        $savePath = $savePath . $fullName;
        //创建目录并设置权限
        $basePath = dirname($savePath);
        if (!file_exists($basePath)) {
            @mkdir($basePath, 0777, true);
            @chmod($basePath, 0777);
        }
        if (file_put_contents($savePath, $body)) {
            return [
                'save_path' => $savePath,
                'file_name' => $fullName,
            ];
        }
        return false;
    }


    /**
     * Notes: 获取项目路径
     * @return string
     * @author 段誉(2021/7/7 18:18)
     */
    public static function getProjectPath()
    {
        $path = dirname(ROOT_PATH);
        if(substr($path, -1) != '/') {
            $path = $path . '/';
        }
        return $path;
    }



    /**
     * Notes: 执行指定文件夹内的sql文件
     * @param $dir
     * @return bool
     * @author 段誉(2021/7/7 18:13)
     */
    public static function upgradeSql($dir)
    {
        //没有sql文件时无需更新
        if (!file_exists($dir)) {
            return true;
        }

        //遍历指定目录下的指定后缀文件
        $sql_files = get_scandir($dir, '', 'sql');
        if (false === $sql_files) {
            return false;
        }

        //当前数据库前缀
        $sql_prefix = config('database.connections.mysql.prefix');

        foreach ($sql_files as $k => $sql_file) {
            if (get_extension($sql_file) != 'sql') {
                continue;
            }
            $sql_content = file_get_contents($dir . $sql_file);
            if (empty($sql_content)) {
                continue;
            }
            $sqls = explode(';', $sql_content);
            //执行sql
            foreach ($sqls as $sql) {
                if (!empty($sql)) {
                    $sql = str_replace('`ls_', '`' . $sql_prefix, $sql) . ';';
                    Db::execute($sql);
                }
            }
        }
        return true;
    }


    /**
     * Notes: 更新文件
     * @param $temp_file //临时更新文件路径 (新的更新文件)
     * @param $old_file //需要更新的文件路囧 (旧的文件)
     * @author 段誉(2021/7/7 18:18)
     * @return bool|int
     */
    public static function upgradeDir($temp_file, $old_file)
    {
        if (empty(trim($temp_file)) || empty(trim($old_file))) {
            return false;
        }

        // 目录不存在就新建
        if (!is_dir($old_file)) {
            mkdir($old_file, 0777, true);
        }

        foreach (glob($temp_file . '*') as $file_name) {
            // 要处理的是目录时,递归处理文件目录。
            if (is_dir($file_name)) {
                self::upgradeDir($file_name . '/', $old_file . basename($file_name) . '/');
            }
            // 要处理的是文件时,判断是否存在 或者 与原来文件不一致 则覆盖
            if (is_file($file_name)) {
                if (!file_exists($old_file . basename($file_name))
                    || md5(file_get_contents($file_name)) != md5(file_get_contents($old_file . basename($file_name)))
                ) {
                    copy($file_name, $old_file . basename($file_name));
                }
            }
        }
        return true;
    }


    /**
     * Notes: 更新本地版本号
     * @param $data
     * @return bool
     * @author 段誉(2021/7/7 18:13)
     */
    public static function upgradeVersion($data)
    {
        $version = './upgrade/version.json';
        $res = file_put_contents($version, json_encode($data, JSON_UNESCAPED_UNICODE));
        if (empty($res)) {
            return false;
        }
        return true;
    }


    public static function getPkgLine($params){
        switch ($params['update_type']) {
            case 1:
                //一键更新类型 : 服務端更新包
                $params['link'] = 'package_link';
                break;
            case 2:
                //服務端更新包
                $params['link'] = 'package_link';
                break;
            case 3:
                //pc端更新包
                $params['link'] = 'pc_package_link';
                break;
            case 4:
                //uniapp更新包
                $params['link'] = 'uniapp_package_link';
                break;
            case 5:
                //后台前端更新包
                $params['link'] = 'web_package_link';
                break;
            case 6:
                //完整包
                $params['link'] = 'integral_package_link';
                break;
        }
        // 授权验证
        $result = self::verify($params);
        if (!$result['has_permission']) {
            // 写日志
            self::addlog($params,false);
            return false;
        }
        //增加日志记录
        self::addlog($params, true);
        //更新包下载链接
        return ['line' => $result['link']];
    }

    /**
     * @notes 验证是否授权
     * @param $params
     * @return array|mixed|\Requests_Response
     * @author cjhao
     * @date 2021/10/27 18:10
     */
    public static function verify($params)
    {

        $domain = $_SERVER['SERVER_NAME'];
        $remoteUrl = self::$base_url . "/api/version/verify?domain=".$domain."&product_id=4&type=2&version_id=".$params['id']."&link=".$params['link'];
        $result = Requests::get($remoteUrl);
        $result = json_decode($result->body, true);
        $result = $result['data'] ?? ['has_permission' => false, 'link' => ''];
        return $result;
    }


}