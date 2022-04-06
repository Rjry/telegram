<?php

namespace app\model;
use think\Model;

class ApiLog extends Model
{
    // 当前模型对应的数据表名称
    protected $name = 'api_log';

    // 当前模型对应的数据表主键
    protected $pk   = 'id';

    // 自动写入和更新时间戳
    protected $autoWriteTimestamp = true;

    // 设置JSON类型字段
    protected $json = ['params', 'result'];

    // 模型初始化
    protected static function init()
    {
        // TODO:初始化内容
    }
}