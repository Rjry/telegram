<?php

// [ JWT ]
/*
use app\api\libs\JWT;

// 设置授权
$token   = JWT::set($args);

// 获取载荷
$payload = JWT::get($token);

// 检查授权
$bool    = JWT::check($token);
*/

namespace app\api\libs;

class JWT
{
    // 密钥
    private static $key    = 'c665daa02a4e7b17';
    // 有效期
    public  static $expire = 86400;

    // 设置授权
    public static function set($args)
    {
        $header  = [
            'typ' => 'JWT',
            'alg' => 'MD5',
        ];
        $payload = [
            'iat' => time(),
            'exp' => time() + self::$expire,
        ];
        $payload   = array_merge($args,$payload);
        $header    = json_encode($header,JSON_UNESCAPED_UNICODE);
        $payload   = json_encode($payload,JSON_UNESCAPED_UNICODE);
        $token     = base64_encode($header) . '.' . base64_encode($payload);
        $signature = self::_signature($token);
        $token    .= '.' . $signature;
        return $token;
    }

    // 获取载荷
    public static function get($token)
    {
        $arr = explode('.', $token);

        list($header,$payload,$sign) = $arr;

        $token      = $header . '.' . $payload;
        $signature  = self::_signature($token);

        $payload = base64_decode($payload);
        $payload = json_decode($payload,true);

        return $payload;
    }

    // 检查授权
    public static function check($token)
    {
        $arr = explode('.', $token);
        if ( 3 != count($arr) ) return false;

        list($header,$payload,$sign) = $arr;

        $token      = $header . '.' . $payload;
        $signature  = self::_signature($token);
        if ($sign != $signature) return false;

        $payload = base64_decode($payload);
        $payload = json_decode($payload,true);

        if ( $payload['iat'] > time() ) return false;
        if ( $payload['exp'] < time() ) return false;

        return true;
    }

    // ---------------------------------------------------------

    // 签名
    private static function _signature($token)
    {
        $key   = md5(self::$key);
        $token = md5( $key . md5($token) . $key );
        return $token;
    }
}