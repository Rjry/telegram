<?php

namespace mailer;

class SMTP
{
    public static $driver = [

        /*阿里云*/
        'aliyun' => [
            'host'   => 'smtpdm-ap-southeast-1.aliyun.com',
            'secure' => 'ssl',
            'port'   => 465,
        ],

        /*谷歌邮箱*/
        'gmail_ssl' => [
            'host'   => 'smtp.gmail.com',
            'secure' => 'ssl',
            'port'   => 465,
        ],

        /*163企业邮箱*/
        '163_crop_ssl' => [
            'host'   => 'smtp.ym.163.com',
            'secure' => '',
            'port'   => 25,
        ],

        /*163邮箱*/
        '163_ssl' => [
            'host'   => 'smtp.163.com',
            'secure' => 'ssl',
            'port'   => 465,
        ],

        /*126邮箱*/
        '126_ssl' => [
            'host'   => 'smtp.126.com',
            'secure' => 'ssl',
            'port'   => 465,
        ],

        /*QQ邮箱*/
        'qq_tls' => [
            'host'   => 'smtp.qq.com',
            'secure' => 'tls',
            'port'   => 587,
        ],

    ];

    public static function get($driver)
    {
        return self::$driver[$driver];
    }
}