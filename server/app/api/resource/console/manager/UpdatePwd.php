<?php

namespace app\api\resource\console\manager;
use app\model\Manager as ManagerModel;

class UpdatePwd
{
    /*是否鉴权*/
    public $token      = true;
    
    /*前置检查*/
    public $pre        = ['manager'];
    
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
    public $apiName    = '控制台 > 管理员 > 修改密码';
    
    /*接口路径*/
    public $apiPath    = 'console.manager.updatePwd';
    
    /*接口配置*/
    public $apiArgs    = [];
    
    /*接口参数*/
    public $apiParams  = [
        'passport' => '旧登录密码',
        'password' => '新登录密码',
    ];

    public function run()
    {
        $passport = $this->params['passport'] ?? '';
        $password = $this->params['password'] ?? '';

        if ( empty($passport) ) return [1,'旧登录密码必须'];
        if ( empty($password) ) return [1,'新登录密码必须'];

        $uuid = $this->payload['uuid'];

        $manager = ManagerModel::where('uuid',$uuid)->where('password',md5($passport))->find();
        if ( is_null($manager) ) return [1,'旧登陆密码错误'];

        $manager->password = md5($password);
        $bool = $manager->save();

        return $bool ? [0,'修改成功'] : [1,'修改失败'];
    }
}