<?php
// +----------------------------------------------------------------------
// | Workerman设置 仅对 php think worker:gateway 指令有效
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 扩展自身需要的配置
    'protocol'              => 'websocket', // 协议 支持 tcp udp unix http websocket text
    'host'                  => '0.0.0.0',   // 监听地址
    'port'                  => 9527,        // 监听端口
    'socket'                => '',          // 完整监听地址
    'context'               => [],          // socket上下文选项
    'register_deploy'       => true,        // 是否需要部署register
    'businessWorker_deploy' => true,        // 是否需要部署businessWorker
    'gateway_deploy'        => true,        // 是否需要部署gateway

    // Register配置
    'registerAddress'       => '127.0.0.1:1236',

    // Gateway配置
    'name'                  => 'thinkphp',
    'count'                 => 1,
    'lanIp'                 => '127.0.0.1',
    'startPort'             => 2000,
    'daemonize'             => false,
    'pingInterval'          => 55,
    'pingNotResponseLimit'  => 0,
    'pingData'              => '{"type":"ping"}',

    // BusinsessWorker配置
    'businessWorker'        => [
        'name'         => 'BusinessWorker',
        'count'        => 1,
        'eventHandler' => '\app\worker\Events',
    ],

];