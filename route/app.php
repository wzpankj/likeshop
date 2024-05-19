<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Config;
use think\facade\Route;


//手机h5页面路由
Route::rule('mobile/:any', function () {
    Config::set(['app_trace' => false]);
    return view(app()->getRootPath() . 'public/mobile/index.html');
})->pattern(['any' => '\w+']);
