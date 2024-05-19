<?php
return [
    'file_domain' => env('project.file_domain', ''),
    'sms' => env('project.sms', true),
    'version' => env('project.version', '1.0.8'),
    'front_version' => env('project.version', '1.0.8'),
    // 缓存过期时间 7天 24*60*60 = 86400 秒
    'token_expire_time' => 86400
];