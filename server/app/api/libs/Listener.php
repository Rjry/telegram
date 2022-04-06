<?php

namespace app\api\libs;
use think\facade\Config;
use app\model\ApiLog as ApiLogModel;
use app\api\libs\Response as ApiResponse;

class Listener
{
    public static function run($reqMethod, $reqPath, $reqParams, $reqIp, $reqTime, $repTime, $repResult)
    {
        if ('wiki' == $reqPath) return true;

        $monitor = Config::get('api.wiki.monitor');
        $listens = Config::get('api.wiki.listens');
        if ( !$monitor ) return false;
        if ( !empty($listens) && !in_array($reqPath,$listens) ) return false;

        $apiLog  = ApiLogModel::create([
            'method' => $reqMethod,
            'path'   => $reqPath,
            'params' => $reqParams,
            'req_ip' => $reqIp,
            'req_at' => $repTime - $reqTime,
            'result' => ApiResponse::run($repResult,'arr'),
        ]);
    }
}