<?php

namespace app\api\resource;
use redis\facade\Queue;

class Cgi
{
    /*是否鉴权*/
    public $token      = false;
    
    /*前置检查*/
    public $pre        = [];
    
    /*请求类型*/
    public $method     = [];
    
    /*头部信息*/
    public $header     = [];
    
    /*请求参数*/
    public $params     = [];
    
    /*请求对象*/
    public $request    = [];
    
    /*鉴权载荷*/
    public $payload    = [];
    
    /*接口名称*/
    public $apiName    = '机器人 | 网关';
    
    /*接口路径*/
    public $apiPath    = 'bot.cgi';
    
    /*接口配置*/
    public $apiArgs    = [];
    
    /*接口参数*/
    public $apiParams  = [];

    public function run()
    {
        $dat = $this->params;

        Queue::name('bot_list')->lpush( serialize($dat) );

        // return [0,'ok'];
    }
}