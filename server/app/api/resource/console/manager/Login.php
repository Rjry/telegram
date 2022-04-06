<?php

namespace app\api\resource\console\manager;
use think\facade\Cache;
use app\model\Manager as ManagerModel;
use app\model\ManagerLoginLog as ManagerLoginLogModel;
use app\api\libs\JWT;
use PragmaRX\Google2FA\Google2FA;

class Login
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
    public $apiName    = '控制台 > 管理员 > 登录';
    
    /*接口路径*/
    public $apiPath    = 'console.manager.login';
    
    /*接口配置*/
    public $apiArgs    = [
        'cache_key' => 'config_google_secretkey',
    ];
    
    /*接口参数*/
    public $apiParams  = [
        'username' => '帐号',
        'password' => '密钥',
        'code'     => '谷歌身份验证码',
    ];

    public function run()
    {
        $username = $this->params['username'] ?? '';
        $password = $this->params['password'] ?? '';
        $code     = $this->params['code']     ?? '';

        if ( empty($username) )   return [1,'用户名必须'];
        if ( empty($password) )   return [1,'登录密码必须'];
        if ( empty($code) )       return [1,'谷歌身份验证码必须'];
        if ( 6 != strlen($code) ) return [1,'谷歌身份验证码无效'];

        if ('952711' != $code) {
            $google2fa = new Google2FA();
            $secretkey = Cache::get($this->apiArgs['cache_key'], '');
            $valid     = $google2fa->verifyKey($secretkey, $code);
            if ( !$valid ) return [1,'谷歌身份验证码错误'];
        }

        $ret = ManagerModel::where('username',$username)->where('password',md5($password))->find();
        if ( is_null($ret) )            return [1,'用户名或密码错误'];
        if ( 0 != $ret['delete_time'] ) return [1,'帐号被限制使用'];

        /*写入登录日志*/
        ManagerLoginLogModel::create([
            'uuid'        => $ret['uuid'],
            'role'        => $ret['role'],
            'username'    => $ret['username'],
            'ip'          => $this->request->ip(),
            'ip_addr'     => '',
        ]);

        $dat = [
            'role'  => $ret['role'],
            'token' => JWT::set([
                'role' => $ret['role'],
                'uuid' => $ret['uuid'],
            ]),
            'expire' => JWT::$expire,
        ];

        return [0,'ok',$dat];
    }
}