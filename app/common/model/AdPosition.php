<?php
namespace app\common\model;

use app\common\basics\Models;
use app\common\enum\AdEnum;
use app\common\enum\DefaultConfigEnum;

/**
 * 广告位模型
 * Class AdPosition
 * @package app\common\model
 */
class AdPosition extends Models
{
    /**
     * @notes 渠道
     * @param $value
     * @param $data
     * @return array|mixed|string|string[]
     * @author ljj
     * @date 2021/10/11 5:57 下午
     */
    public function getTerminalDescAttr($value,$data)
    {
        return AdEnum::getTerminal($data['terminal']);
    }

    /**
     * @notes 广告位类型
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2021/10/11 6:04 下午
     */
    public function getAttrDescAttr($value,$data)
    {
        return AdEnum::getType($data['attr']);
    }

    /**
     * @notes 广告位状态
     * @param $value
     * @param $data
     * @return string|string[]
     * @author ljj
     * @date 2021/10/12 9:57 上午
     */
    public function getStatusDescAttr($value,$data)
    {
        return DefaultConfigEnum::getAdStatusDesc($data['status']);
    }
}