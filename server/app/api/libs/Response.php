<?php

namespace app\api\libs;

class Response
{
    // 输出格式
    public static $format = ['json','jsonp','xml','arr','text'];

    // 执行输出
    public static function run($data, $format = 'json')
    {
        $format = in_array($format,self::$format) ? $format : 'json';
        return self::$format($data);
    }

    // JSON数据输出
    public static function json($data)
    {
        return json([
            'code' => $data[0],
            'msg'  => $data[1],
            'data' => $data[2] ?? null,
        ]);
    }

    // JSONP数据输出
    public static function jsonp($data)
    {
        return jsonp([
            'code' => $data[0],
            'msg'  => $data[1],
            'data' => $data[2] ?? null,
        ]);
    }

    // XML数据输出
    public static function xml($data)
    {
        return xml([
            'code' => $data[0],
            'msg'  => $data[1],
            'data' => $data[2] ?? null,
        ]);
    }

    // 数组输出
    public static function arr($data)
    {
        return [
            'code' => $data[0],
            'msg'  => $data[1],
            'data' => $data[2] ?? null,
        ];
    }

    // TEXT数据输出
    public static function text($data)
    {
        return is_string($data[2]) ? $data[2] : json($data[2]);
    }
}