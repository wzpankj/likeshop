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
namespace app\admin\validate\decoration\ad;

use app\common\basics\Validate;
use app\common\model\Ad;
use app\common\model\AdPosition;

class AdValidate extends Validate{
    protected $rule = [
        'id' => 'require|checkId',
        'title' => 'require|checkTitle',
        'ad_position_id' => 'require|checkPid',
        'image' => 'require',
        'status' => 'require|in:0,1'

    ];

    protected $message = [
        'id.require'        => '参数缺失',
        'title.require'     => '请输入广告标题',
        'ad_position_id.require'       => '请选择广告位',
        'image.require'     => '请上传广告图',
        'status.require'     => '请选择状态',
    ];


    public function sceneAdd(){
        return $this->only(['title','ad_position_id','image','status']);
    }

    public function sceneEdit(){
        return $this->only(['id','title','ad_position_id','image','status']);
    }

    public function sceneDel(){
        return $this->only(['id']);
    }

    public function sceneStatus(){
        return $this->only(['id','status']);
    }

    /**
     * @notes 检验广告id
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/12 10:42 上午
     */
    public function checkId($value,$rule,$data)
    {
        $result = Ad::where(['id'=>$value,'del'=>0])->findOrEmpty();

        if($result->isEmpty()){
            return '广告不存在';
        }
        return true;
    }

    /**
     * @notes 检验广告位
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/12 10:43 上午
     */
    public function checkPid($value,$rule,$data)
    {
        $result = AdPosition::where(['id'=>$value,'del'=>0])->findOrEmpty();

        if($result->isEmpty()){
            return '广告位不存在';
        }
        return true;
    }

    /**
     * @notes 检验广告名称
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/12 10:44 上午
     */
    public function checkTitle($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['title', '=', $value];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = Ad::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '广告名称已存在';
        }

        return true;
    }
}