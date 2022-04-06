<?php

namespace redis\facade;
use think\Facade;

class Hash extends Facade
{
    protected static function getFacadeClass()
    {
        return 'redis\Hash';
    }
}