<?php

namespace app\controller;
use app\BaseController;
use think\facade\Db;
use think\facade\Env;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Console;
use think\facade\View;

use PragmaRX\Google2FA\Google2FA;
use redis\facade\Hash;

class Debug extends BaseController
{
    // http://localhost:8000/debug
    public function index()
    {
        // return 'debug';
        // return View::fetch();
    }

}