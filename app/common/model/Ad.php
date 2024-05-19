<?php
namespace app\common\model;

use app\common\basics\Models;
use app\common\enum\AdEnum;
use app\common\enum\DefaultConfigEnum;

class Ad extends Models
{
    /**
     * @notes 广告状态
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

    /**
     * @notes 渠道
     * @param $value
     * @param $data
     * @return array|mixed|string|string[]
     * @author ljj
     * @date 2021/10/12 10:00 上午
     */
    public function getTerminalDescAttr($value,$data)
    {
        return AdEnum::getTerminal($data['terminal']);
    }
}