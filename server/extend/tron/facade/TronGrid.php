<?php

namespace tron\facade;
use think\Facade;

class TronGrid extends Facade
{
    protected static function getFacadeClass()
    {
        return 'tron\TronGrid';
    }
}