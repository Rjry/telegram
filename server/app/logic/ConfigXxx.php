<?php

// +----------------------------------------------------------------------------------------------------------------
// | [ 配置 | XXX ]
// +----------------------------------------------------------------------------------------------------------------
/*
    # 获取
    $config = \app\logic\ConfigXxx::get();
 
    # 设置
    $config = [
        'xxx' => '',
        'xxx' => '',
    ];
    \app\logic\ConfigXxx::set($config);
*/

namespace app\logic;
use redis\facade\Hash;

class ConfigXxx
{
    const NAME = 'config_xxx';

    public static $config = [
        /*xxx*/
        'xxx' => '',
        /*xxx*/
        'xxx' => '',
    ];

    public static function get($key = '')
    {
        if ( empty($key) ) {
            return Hash::name(self::NAME)->all() ?: self::$config;
        } else {
            $res = Hash::name(self::NAME)->all() ?: self::$config;
            return $res[$key];
        }
    }

    public static function set($config)
    {
        foreach ($config as $key => $val) {
            isset(self::$config[$key]) && Hash::name(self::NAME)->set($key,$val);
        }
    }
}