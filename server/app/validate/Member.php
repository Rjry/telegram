<?php

namespace app\validate;
use think\Validate;

class Member extends Validate
{
    protected $rule = [
        'rid'      => 'require|length:8',
        'username' => 'require|email',
        'passport' => 'require|length:6,16',
        'password' => 'require|length:6,16',
        'tradepwd' => 'require|length:6,16',
        'code'     => 'require|number|length:6',
    ];

    protected $message = [
        'rid.require'      => 'Invalid referid',
        'rid.length'       => 'Referid length[8]',
        
        'username.require' => 'Invalid email',
        'username.email'   => 'Invalid email',
        
        'passport.require' => 'Invalid old password',
        'passport.length'  => 'Old password length[6~16]',
        
        'password.require' => 'Invalid password',
        'password.length'  => 'Password length[6~16]',
        
        'tradepwd.require' => 'Invalid transaction password',
        'tradepwd.length'  => 'Transaction password length[6~16]',
        
        'code.require'     => 'Invalid phone code',
        'code.number'      => 'Invalid phone code',
        'code.length'      => 'Phone code length[6]',
    ];

    protected $scene   = [
        'login'         => ['username','password'],
        'register'      => ['username','code','password'],
        'resetPwd'      => ['username','code','password'],
        'resetTradePwd' => ['username','code','tradepwd'],
        'updatePwd'     => ['passport','password'],
    ];
}