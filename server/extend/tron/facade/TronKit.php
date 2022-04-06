<?php

namespace tron\facade;
use think\Facade;

class TronKit extends Facade
{
    protected static function getFacadeClass()
    {
        return 'tron\TronKit';
    }
}