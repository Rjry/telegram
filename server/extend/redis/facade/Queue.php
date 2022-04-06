<?php

namespace redis\facade;
use think\Facade;

class Queue extends Facade
{
    protected static function getFacadeClass()
    {
        return 'redis\Queue';
    }
}