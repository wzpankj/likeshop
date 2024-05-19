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
namespace app\admin\logic\decoration\ad;

use app\common\basics\Logic;
use app\common\model\AdPosition;
use think\facade\Db;

/**
 * 广告位逻辑层
 * Class AdPositionLogic
 * @package app\admin\logic\decoration\ad
 */
class AdPositionLogic extends Logic
{
    /**
     * @notes 广告位列表
     * @param $get
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/11 6:08 下午
     */
    public static function lists($get)
    {
        $count = AdPosition::where(['del'=>0])->count();
        $lists = AdPosition::where(['del'=>0])
            ->append(['terminal_desc','attr_desc','status_desc'])
            ->page($get['page'], $get['limit'])
            ->select()
            ->toArray();

        return ['count'=>$count,'lists'=>$lists];
    }

    /**
     * @notes 添加广告位
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/11 6:31 下午
     */
    public static function add($post)
    {
        if(!empty(trim($post['size']))) {
            $tempArr = explode('*', $post['size']);
            $post['width'] = $tempArr[0];
            $post['height'] = $tempArr[1];
        }

        AdPosition::create([
            'name'  =>  $post['name'],
            'width' =>  $post['width'] ?? '',
            'height' =>  $post['height'] ?? '',
            'status' =>  $post['status'],
            'create_time' =>  time(),
        ]);

        return true;
    }

    /**
     * @notes 编辑广告位
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/11 6:45 下午
     */
    public static function edit($post)
    {
        if(!empty(trim($post['size']))) {
            $tempArr = explode('*', $post['size']);
            $post['width'] = $tempArr[0];
            $post['height'] = $tempArr[1];
        }

        AdPosition::update([
            'name'  =>  $post['name'],
            'width' =>  $post['width'] ?? '',
            'height' =>  $post['height'] ?? '',
            'status' =>  $post['status'],
            'create_time' =>  time(),
        ],['id'=>$post['id']]);

        return true;
    }

    /**
     * @notes 获取广告位详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/11 6:45 下午
     */
    public static function getDetail($id)
    {
        $result =  AdPosition::where(['id'=>$id,'del'=>0])->find()->toArray();

        if(!empty($result['width']) && !empty($result['height'])) {
            $result['size'] = $result['width'] . '*' . $result['height'];
        }else{
            $result['size'] = '';
        }

        return $result;
    }

    /**
     * @notes 删除广告位
     * @param $id
     * @return bool
     * @author ljj
     * @date 2021/10/11 6:57 下午
     */
    public static function del($id)
    {
        AdPosition::update(['del'=>1,'update_time'=>time()],['id'=>$id]);
        return true;
    }

    /**
     * @notes 更新广告位状态
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/11 7:02 下午
     */
    public static function status($post)
    {
        AdPosition::update(['status'=>$post['status'],'update_time'=>time()],['id'=>$post['id']]);
        return true;
    }


}
