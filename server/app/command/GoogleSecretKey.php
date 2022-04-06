<?php

// [谷歌身份验证器密钥]
// php think googleSecretKey
// php think googleSecretKey init

declare (strict_types = 1);

namespace app\command;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Cache;
use PragmaRX\Google2FA\Google2FA;

class GoogleSecretKey extends Command
{
    const CACHE_KEY = 'config_google_secretkey';

    protected function configure()
    {
        // 指令配置
        $this->setName('initGoogleSecretKey')
             ->addArgument('ins', Argument::OPTIONAL, 'ins')
             ->setDescription('The GoogleSecretKey Command');
    }

    protected function execute(Input $input, Output $output)
    {
        $ins = $input->getArgument('ins') ?? '';
        if ( empty($ins) ) {
            $res = Cache::get(self::CACHE_KEY, '');

            $log = '谷歌身份验证器密钥：' . $res;
            $output->writeln($log);
        } else {
            $google2fa = new Google2FA();
            $secretkey =  $google2fa->generateSecretKey();

            Cache::set(self::CACHE_KEY, $secretkey);

            $log = '谷歌身份验证器密钥：' . $secretkey;
            $output->writeln($log);
        }
    }
}