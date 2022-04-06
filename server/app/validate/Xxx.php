<?php

namespace app\validate;
use think\Validate;

class Xxx extends Validate
{
    protected $rule = [
        'attr'     => 'require|attr|max:60',
        'username' => 'require|length:4,16',
        'nickname' => 'require|chsDash|length:2,12',
        'passport' => 'require|length:6,16',
        'password' => 'require|length:6,16',
        'tradepot' => 'require|length:6,16',
        'tradepwd' => 'require|length:6,16',
        'wlt_addr' => 'require|length:10,80',
    ];

    protected $message = [
        'attr.require'     => '推荐码必须',
        'attr.attr'        => '推荐码无效',
        'attr.max'         => '推荐码长度[<60]',
        'username.require' => '用户名必须',
        'username.length'  => '用户名长度[4~16]',
        'nickname.require' => '昵称必须',
        'nickname.chsDash' => '昵称无效',
        'nickname.length'  => '昵称长度[2~12]',
        'passport.require' => '旧登录密码必须',
        'passport.length'  => '旧登录密码长度[6~16]',
        'password.require' => '登录密码必须',
        'password.length'  => '登录密码长度[6~16]',
        'tradepot.require' => '旧交易密码必须',
        'tradepot.length'  => '旧交易密码长度[6~16]',
        'tradepwd.require' => '交易密码必须',
        'tradepwd.length'  => '交易密码长度[6~16]',
        'wlt_addr.require' => '钱包地址必须',
        'wlt_addr.length'  => '钱包地址长度[10,80]',
    ];

    protected $scene   = [
        'login'       => ['username','password'],
        'register'    => ['attr','username','password','tradepwd'],
        'setNickname' => ['nickname'],
        'setPassword' => ['passport','password'],
        'setTradepwd' => ['tradepot','tradepwd'],
        'setWltAddr'  => ['wlt_addr','tradepwd'],
    ];

    // ---------------------------------------------------------
    
    protected function attr($value, $rule, $data = [])
    {
        $result = decode($value);
        return is_numeric($result) ? true : false;
    }
}