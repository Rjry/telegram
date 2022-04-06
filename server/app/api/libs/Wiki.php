<?php

namespace app\api\libs;
use think\facade\Cache;
use think\facade\Config;

class Wiki
{
    // 缓存键名
    public static function key()
    {
        return Config::get('api.suff') . '_api_wiki';
    }

    // 获取配置
    public static function run()
    {
        $key = self::key();
        $res = Cache::get($key);
        if (!$res) {
            $dir = app_path() . 'api' . DS . 'resource';
            $arr = self::loop($dir);
            $res = self::merg($arr);
            Cache::set($key,$res);
        }
        return $res;
    }

    // 遍历资源
    private static function loop($dir, &$ret = [])
    {
        $arr = scandir($dir);
        unset($arr[0],$arr[1]);
        sort($arr);
        foreach ($arr as $key => $val) {
            $file = $dir . DIRECTORY_SEPARATOR . $val;
            if ( is_dir($file) ) {
                self::loop($file,$ret);
            } else {
                $ret[] = $file;
            }
        }
        return $ret;
    }

    // 填装资源
    private static function merg($arr)
    {
        $ret = [];
        foreach($arr as $key => $val) {
            preg_match('/resource(.*)\.php/',$val,$mat);
            $mat   = $mat[1];
            $mat   = substr($mat,1);
            $mat   = str_replace('/','\\',$mat);
            $cmd   = str_replace('\\','.',$mat);
            $cmd   = explode('.',$cmd);
            foreach ($cmd as $k => $v) {
                if (end($cmd) == $v) $cmd[$k] = lcfirst($v);
            }
            $cmd   = implode('.',$cmd);
            $map   = "\\app\\api\\resource\\" . $mat;
            $ret[$cmd] = $map;
        }
        return $ret;
    }
}