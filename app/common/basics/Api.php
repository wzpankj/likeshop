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

namespace app\common\basics;


use think\App;

/**
 * API接口基类
 * Class Api
 * @Author FZR
 * @package app\common\basics
 */
abstract class Api
{
    /**
     * Request实例
     */
    protected $request;

    /**
     * 应用实例
     */
    protected $app;

    /**
     * 用户ID
     * @var int
     */
    protected $user_id = null;

    /**
     * 用户信息
     * @var array
     */
    public $user_info = [];

    /**
     * 客户端
     * @var null
     */
    public $client = null;

    /**
     * 页码
     * @var int
     */
    public $page_no = 1;

    /**
     * 每页显示条数
     * @var int
     */
    public $page_size = 15;

    /**
     * 无需登录即可访问的方法
     * @var array
     */
    public $like_not_need_login = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = app();
        $this->request = request();


        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        //用户信息
        $this->user_info = $this->request->user_info ?? [];
        if(boolval($this->user_info)) {
            $this->user_id = $this->user_info['id'] ?? null;
            $this->client = $this->user_info['client'] ?? null;
        }

        //分页参数
        $page_no = (int)$this->request->get('page_no');
        $this->page_no = $page_no && is_numeric($page_no) ? $page_no : $this->page_no;
        $page_size = (int)$this->request->get('page_size');
        $this->page_size = $page_size && is_numeric($page_size) ? $page_size : $this->page_size;
        $this->page_size = min($this->page_size, 100);
    }
}