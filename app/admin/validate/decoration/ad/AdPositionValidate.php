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
use app\common\enum\AdEnum;
use app\common\model\Ad;
use app\common\model\AdPosition;
use think\facade\Db;

/**
 * 广告位验证器
 * Class AdPositionValidate
 * @package app\admin\validate\decoration\ad
 */
class AdPositionValidate extends Validate{
    protected $rule = [
        'id'        => 'require|checkId',
        'name'      => 'require|checkName',
        'status'    => 'require|in:0,1'
    ];

    protected $message = [
        'id.require'        => '参数缺失',
        'name.require'    => '请输入广告位名称',
        'status.require'    => '状态不能为空',
    ];

    public function sceneAdd()
    {
        return $this->only(['name','status']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','name','status']);
    }

    public function sceneDel()
    {
        return $this->only(['id'])
            ->append('id','checkDel');
    }

    public function sceneStatus()
    {
        return $this->only(['id','status']);
    }

    /**
     * @notes 检验广告位id
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/11 6:12 下午
     */
    public function checkId($value,$rule,$data)
    {
        $result = AdPosition::where(['del'=>0,'id'=>$value])->findOrEmpty();

        if ($result->isEmpty()) {
            return '广告位不存在';
        }

        return true;
    }

    /**
     * @notes 检验广告位名称
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/11 6:16 下午
     */
    public function checkName($value,$rule,$data)
    {
        $where[] = ['del', '=', 0];
        $where[] = ['name', '=', $value];
        if (isset($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $result = AdPosition::where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            return '广告位名称已存在';
        }

        return true;
    }

    /**
     * @notes 检验广告位是否能删除
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     * @author ljj
     * @date 2021/10/11 6:56 下午
     */
    public function checkDel($value,$rule,$data)
    {
        $result = AdPosition::where(['del'=>0,'id'=>$value])->findOrEmpty();
        if ($result['attr'] == AdEnum::TYPE_DEFAULT) {
            return '系统默认广告位不能删除';
        }

        $result = Ad::where(['del'=>0,'ad_position_id'=>$value])->count();
        if ($result > 0) {
            return '当前广告位已使用，需移除广告后删除';
        }

        return true;
    }
}