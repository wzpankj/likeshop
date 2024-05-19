<?php
// +----------------------------------------------------------------------
// | likeshop100%开源免费商用商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | 商业版本务必购买商业授权，以免引起法律纠纷
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshopTeam
// +----------------------------------------------------------------------
namespace app\common\enum;


/**
 * 默认配置枚举
 * Class DefaultConfigEnum
 * @package app\common\enum
 */
class DefaultConfigEnum
{
    const NO    = 0;
    const YES   = 1;

    // 排序：默认50
    const SORT = 50;

    /**
     * @notes 门店营业状态
     * @param bool $from
     * @return array|mixed|string
     * @author cjhao
     * @date 2021/8/31 15:39
     */
    public static function getShopStatusDesc($from = true)
    {
        $desc = [
            self::YES   => '营业',
            self::NO    => '停业',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 打印机状态
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2021/9/23 12:00 下午
     */
    public static function getPrinterStatusDesc($from = true)
    {
        $desc = [
            self::YES   => '开启',
            self::NO    => '关闭',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 是否显示
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2021/10/9 4:54 下午
     */
    public static function getIsShowDesc($from = true)
    {
        $desc = [
            self::YES   => '是',
            self::NO    => '否',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 广告位状态
     * @param bool $from
     * @return string|string[]
     * @author ljj
     * @date 2021/10/11 6:06 下午
     */
    public static function getAdStatusDesc($from = true)
    {
        $desc = [
            self::YES   => '启用',
            self::NO    => '停用',
        ];
        if(true === $from){
            return $desc;
        }
        return $desc[$from] ?? '';
    }


}