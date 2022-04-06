<?php

// [ 接口模块配置文件 ]

return [
    
    /*前缀*/
    'suff' => 'server',
    /*文档*/
    'wiki' => [
        /*视图开关*/
        'viewer'  => true,
        /*监控开关*/
        'monitor' => true,
        /*监控接口*/
        'listens' => [],
        /*隐藏接口*/
        'hidden'  => [
            'wiki'
        ],
        /*全局状态码*/
        'retCode' => [
            /*en*/
            // 0    => 'Success',
            // 1    => 'Fail',
            // 9001 => 'Invalid request path',
            // 9002 => 'Invalid request method',
            // 9003 => 'missing token | invalid token',
            // 9004 => 'Pre-check failed',

            /*zh*/
            0    => '成功',
            1    => '失败',
            9001 => '无效的接口名称',
            9002 => '无效的请求方式',
            9003 => '缺失令牌或令牌无效',
            9004 => '角色身份权限鉴权失败',
        ],
    ],

];