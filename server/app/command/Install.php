<?php

// [安装环境参数]
// php think install

declare (strict_types = 1);

namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

use redis\facade\Hash;
use think\facade\Db;

class Install extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('install')
             ->setDescription('The Install Command');
    }

    protected function execute(Input $input, Output $output)
    {
        # 初始化管理员
        $num = Db::name('manager')->insert([
            'uuid'        => mk_uuid(),
            'role'        => 'admin',
            'username'    => 'admin',
            'password'    => md5('admin'),
            'create_time' => time(),
            'delete_time' => 0,
        ]);
        $log = '初始化管理员 >> 成功';
        $output->writeln($log);



        $log = '-End-';
        $output->writeln($log);
        
    }
}