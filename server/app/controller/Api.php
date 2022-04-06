<?php

// [ Api ]

namespace app\controller;
use app\BaseController;
use think\facade\Request;
use think\facade\Config;
use think\facade\View;
use app\api\libs\JWT;
use app\api\libs\Wiki as ApiWiki;
use app\api\libs\Listener as ApiListener;
use app\api\libs\Response as ApiResponse;

class Api extends BaseController
{
    // 入口
    // http://localhost:8000/api/
    public function index($_path = '', $_format = 'json')
    {
        /*请求类型*/
        $method = Request::method();

        /*头部信息*/
        $header = Request::header();

        /*请求参数*/
        $params = Request::except(['_path','_format']);

        /*请求Ip*/
        $reqIp    = Request::ip();

        /*请求时间*/
        $reqTime  = millitime();

        /*接口检查*/
        $pathArr  = ApiWiki::run();
        if ( !in_array( $_path,array_keys($pathArr) ) ) return ApiResponse::run([9001,'Invalid request path'],$_format);

        /*接口实例*/
        $api = new $pathArr[$_path];

        /*依赖注入*/
        $api->request = $this->request;
        $api->header  = $header;
        $api->params  = $params;

        /*类型检查*/
        if ( !empty($api->method) && !in_array($method,$api->method) ) return ApiResponse::run([9002,'Invalid request method'],$_format);

        /*令牌检查*/
        if ($api->token) {
            $token = $header['x-api-token'] ?? '';
            if ( empty($token) ) return ApiResponse::run([9003,'missing token'], $_format);
            if ( !JWT::check($token) ) return ApiResponse::run([9003,'invalid token'],$_format);
            /*依赖注入*/
            $api->payload = JWT::get($token);
        }

        /*前置检查*/
        if ( !empty($api->pre) ) {
            foreach ($api->pre as $val) {
                $pre = '\\app\\api\\pre\\' . ucfirst($val);
                $pre = new $pre($api, $this->request);
                if ( !$pre->run() ) return ApiResponse::run($pre->response,$_format);
            }
        }
        
        /*接口调用*/
        $result  = call_user_func_array([$api,'run'],[]);

        /*响应时间*/
        $repTime = millitime();

        /*接口监控*/
        ApiListener::run($method, $_path, $params, $reqIp, $reqTime, $repTime, $result);

        return ApiResponse::run($result,$_format);
    }

    // 文档
    // http://localhost:8000/api/wiki
    public function wiki()
    {
        return Config::get('api.wiki.viewer') ? View::fetch() : miss();
    }
}