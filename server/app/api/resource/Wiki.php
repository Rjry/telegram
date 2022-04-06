<?php

namespace app\api\resource;
use think\facade\Db;
use think\facade\Cache;
use think\facade\Config;
use app\api\libs\Wiki as ApiWiki;
use app\model\ApiLog as ApiLogModel;

class Wiki
{
    /*是否鉴权*/
    public $token      = false;
    
    /*前置检查*/
    public $pre        = [];
    
    /*请求类型*/
    public $method     = ['POST'];
    
    /*头部信息*/
    public $header     = [];
    
    /*请求参数*/
    public $params     = [];
    
    /*请求对象*/
    public $request    = [];
    
    /*鉴权载荷*/
    public $payload    = [];
    
    /*接口名称*/
    public $apiName    = '文档';
    
    /*接口路径*/
    public $apiPath    = 'wiki';
    
    /*接口配置*/
    public $apiArgs    = [];
    
    /*接口参数*/
    public $apiParams  = [
        'cmd'  => '指令,get-获取文档(默认) logs-接口日志 code-全局返码 cls-清除缓存',
        'path' => '接口,可选,默认为空',
    ];

    public function run()
    {
        $cmd  = $this->params['cmd']  ?? 'get';
        $path = $this->params['path'] ?? '';

        switch ($cmd) {
            /*获取文档*/
            case 'get':
                $ret = $this->doGet($path);
                break;
            /*接口日志*/
            case 'logs':
                $ret = $this->doLogs();
                break;
            /*全局返码*/
            case 'code':
                $ret = $this->doCode();
                break;
            /*清除缓存*/
            case 'cls':
                $ret = $this->doCls();
                break;
            /*其它*/
            default:
                $ret = [1,'Invalid cmd'];
                break;
        }
        
        return $ret;
    }

    // 获取文档
    private function doGet($path)
    {
        $key = ApiWiki::key();

        $arr = Cache::get($key);

        $ret = [];

        if ($path) {
            /*详情*/
            $ret = get_object_vars(new $arr[$path]);
        } else {
            /*列表*/
            $arr = array_keys($arr);
            $ret = array_values( array_diff( $arr,Config::get('api.wiki.hidden') ) );
        }

        return [0,'ok',$ret];
    }

    // 接口日志
    private function doLogs($page = 1, $show = 15, $skey = '')
    {
        $ret = ApiLogModel::order('id','DESC')->limit(100)->select()->toArray();
        return [0,'ok',$ret];
    }

    // 全局返码
    private function doCode()
    {
        return [0,'ok',Config::get('api.wiki.retCode')];
    }

    // 清除缓存
    private function doCls()
    {
        $key = ApiWiki::key();

        Cache::delete($key);

        return [0,'ok'];
    }
}