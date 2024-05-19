<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'   => [],
        'HttpRun'   => [],
        'HttpEnd'   => [],
        'LogLevel'  => [],
        'LogWrite'  => [],
        'ShopStat'  => ['app\common\listener\ShopStat'], // 记录统计信息(访问门店用户量)
        'Notice'    => ['app\common\listener\Notice'], // 通知
        'Printer'   => ['app\common\listener\Printer'],//自动打印
    ],

    'subscribe' => [
    ],
];
