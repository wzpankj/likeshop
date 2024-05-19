<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'crontab'            => 'app\common\command\Crontab',
        'order_close'        => 'app\common\command\OrderClose',    //自动关闭未支付订单
        'order_finish'       => 'app\common\command\OrderFinish',   //自动完成订单
        'password'           => 'app\common\command\Password',      //修改密码
    ],
];
