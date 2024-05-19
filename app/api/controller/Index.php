<?php
namespace app\api\controller;

use app\common\basics\Api;
use app\api\logic\IndexLogic;
use app\common\server\JsonServer;

class Index extends Api
{
    public $like_not_need_login = ['config','style'];

    /**
     * @notes 通用配置
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/13 10:39 上午
     */
    public function config()
    {
        $data = IndexLogic::config();
        return JsonServer::success('获取成功', $data);
    }

    /**
     * @notes 商城风格
     * @return \think\response\Json
     * @author ljj
     * @date 2021/10/13 2:23 下午
     */
    public function style()
    {
        return JsonServer::success('', IndexLogic::style());
    }
}