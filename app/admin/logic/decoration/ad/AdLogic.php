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
use app\common\model\Ad;
use app\common\model\AdPosition;

class AdLogic extends Logic
{
    /**
     * @notes 广告列表
     * @param $get
     * @return array
     * @author ljj
     * @date 2021/10/12 10:35 上午]
     */
    public static function lists($get)
    {
        $where[] = ['a.del','=',0];

        if(isset($get['ad_position_id']) && $get['ad_position_id']){
            $where[] = ['a.ad_position_id','=',$get['ad_position_id']];
        }

        $count = Ad::alias('a')
            ->join('ad_position ap','a.ad_position_id = ap.id')
            ->where($where)
            ->count();

        $lists = Ad::alias('a')
            ->join('ad_position ap','a.ad_position_id = ap.id')
            ->where($where)
            ->order('a.id desc')
            ->field('a.*,ap.name as ad_position_name')
            ->append(['status_desc','terminal_desc'])
            ->page($get['page'], $get['limit'])
            ->select()
            ->toArray();

        return ['count'=>$count,'lists'=>$lists];
    }

    /**
     * @notes 获取广告位列表
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/12 10:04 上午
     */
    public static function getPositionList()
    {
        return AdPosition::where(['del'=>0,'status'=>1])->select();
    }

    /**
     * @notes 添加广告
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/12 10:58 上午
     */
    public static function add($post)
    {
        Ad::create([
            'ad_position_id' => $post['ad_position_id'],
            'title' => $post['title'],
            'image' => $post['image'],
            'link' => $post['link'],
            'status' => $post['status'],
            'create_time' => time(),
        ]);

        return true;
    }

    /**
     * @notes 编辑广告
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/12 11:01 上午
     */
    public static function edit($post)
    {
        Ad::update([
            'ad_position_id' => $post['ad_position_id'],
            'title' => $post['title'],
            'image' => $post['image'],
            'link' => $post['link'],
            'status' => $post['status'],
            'update' => time(),
        ],['id'=>$post['id']]);

        return true;
    }

    /**
     * @notes 获取广告详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author ljj
     * @date 2021/10/12 11:01 上午
     */
    public static function getDetail($id)
    {
        return Ad::where(['id'=>$id,'del'=>0])->find()->toArray();
    }

    /**
     * @notes 删除广告
     * @param $id
     * @return bool
     * @author ljj
     * @date 2021/10/12 11:08 上午]
     */
    public static function del($id)
    {
        Ad::update([
            'del' => 1,
            'update' => time(),
        ],['id'=>$id]);

        return true;
    }

    /**
     * @notes 修改广告状态
     * @param $post
     * @return bool
     * @author ljj
     * @date 2021/10/12 11:12 上午
     */
    public static function status($post)
    {
        Ad::update([
            'status' => $post['status'],
            'update' => time(),
        ],['id'=>$post['id']]);

        return true;
    }

    /**
     * @notes 导出列表
     * @param $get
     * @return array
     * @author ljj
     * @date 2021/10/12 11:19 上午
     */
    public static function exportFile($get)
    {
        $where[] = ['a.del','=',0];

        if(isset($get['ad_position_id']) && $get['ad_position_id']){
            $where[] = ['a.ad_position_id','=',$get['ad_position_id']];
        }

        $lists = Ad::alias('a')
            ->join('ad_position ap','a.ad_position_id = ap.id')
            ->where($where)
            ->order('a.id desc')
            ->field('a.*,ap.name as ad_position_name')
            ->append(['status_desc','terminal_desc'])
            ->select()
            ->toArray();

        $exportTitle = ['ID', '渠道', '广告标题', '广告位', '广告链接', '状态'];
        $exportExt = 'xls';
        $exportData = [];
        foreach($lists as $item) {
            $exportData[] = [$item['id'], $item['terminal_desc'], $item['title'], $item['ad_position_name'], $item['link'], $item['status_desc']];
        }

        return ['exportTitle'=> $exportTitle, 'exportData' => $exportData, 'exportExt'=>$exportExt, 'exportName'=>'广告列表'.date('Y-m-d H:i:s',time())];
    }
}
