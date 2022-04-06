<?php

namespace app\validate;
use think\Validate;

class Mail extends Validate
{
    protected $rule    = [
        'scene' => 'require|length:2,18',
        'email' => 'require|email',
    ];

    protected $message = [
        'email.require' => 'Invalid email',
        'email.email'   => 'Invalid email',
        
        'scene.require' => 'Invalid scene',
        'scene.length'  => 'Scene length[2~18]',
    ];

    protected $scene   = [
        'send'       => ['email','scene'],
        'countdown'  => ['email','scene'],
    ];
}