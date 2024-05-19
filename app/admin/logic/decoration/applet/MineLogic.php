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

namespace app\admin\logic\decoration\applet;


use app\common\basics\Logic;
use app\common\model\DecorateMine;
use app\common\server\ConfigServer;
use app\common\server\UrlServer;

/**
 * 我的装修逻辑层
 * Class MineLogic
 * @package app\admin\logic\decoration\applet
 */
class MineLogic extends Logic
{
    /**
     * @notes 我的装修列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 2:59 下午
     */
    public static function lists()
    {
        $count = DecorateMine::where(['del'=>0])->count();
        $lists = DecorateMine::where(['del'=>0])
            ->append(['link_type_desc','link_address_desc','is_show_desc'])
            ->order(['sort'=>'asc','id'=>'asc'])
            ->select();

        return ['count'=>$count,'lists'=>$lists];
    }

    /**
     * @notes 获取其他设置
     * @return array
     * @author ljj
     * @date 2021/10/9 3:21 下午
     */
    public static function getOtherSet()
    {
        $background_image =  ConfigServer::get('decoration_center','background_image','');
        $background_image && $background_image = UrlServer::getFileUrl($background_image);
        $data['background_image'] = $background_image;

        return $data;
    }

    /**
     * @notes 其他设置
     * @param $post
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 3:58 下午
     */
    public static function otherSet($post)
    {
        ConfigServer::set('decoration_center','background_image',UrlServer::setFileUrl($post['background_image'] ?? ''));

        return true;
    }

    /**
     * @notes 添加
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/9 4:47 下午
     */
    public static function add($post)
    {
        DecorateMine::create([
            'name'  =>  $post['name'],
            'image' =>  clearDomain($post['image']),
            'link_type' => $post['link_type'],
            'link_address' => ($post['link_type'] == 1) ? $post['menu'] : $post['url'],
            'sort' => $post['sort'],
            'is_show' => $post['is_show'],
            'create_time' => time(),
        ]);

        return true;
    }

    /**
     * @notes 编辑
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/9 5:05 下午
     */
    public static function edit($post)
    {
        DecorateMine::update([
            'name'  =>  $post['name'],
            'image' =>  clearDomain($post['image']),
            'link_type' => $post['link_type'],
            'link_address' => ($post['link_type'] == 1) ? $post['menu'] : $post['url'],
            'sort' => $post['sort'],
            'is_show' => $post['is_show'],
            'update_time' => time(),
        ],['id'=>$post['id']]);

        return true;
    }

    /**
     * @notes 获取详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/9 5:05 下午
     */
    public static function getDetail($id)
    {
        return DecorateMine::where('id',$id)->find()->toArray();
    }

    /**
     * @notes 修改状态
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/9 5:09 下午
     */
    public static function status($post)
    {
        DecorateMine::update([
            'is_show' => $post['is_show'],
            'update_time' => time(),
        ],['id'=>$post['id']]);

        return true;
    }

    /**
     * @notes 删除
     * @param $id
     * @return bool
     * @author ljj
     * @date 2021/10/9 5:11 下午
     */
    public static function del($id)
    {
        DecorateMine::update([
            'del' => 1,
            'update_time' => time(),
        ],['id'=>$id]);

        return true;
    }
}