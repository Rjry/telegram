<?php

// [ 邮件码 ]
/*
-- 邮件码
DROP TABLE IF EXISTS `mail_code`;
CREATE TABLE `mail_code` (
`id`           int(11)       NOT NULL AUTO_INCREMENT COMMENT '主键',
`scene`        char(20)      NOT NULL DEFAULT ''     COMMENT '场景',
`email`        char(32)      NOT NULL DEFAULT ''     COMMENT '邮箱',
`code`         char(10)      NOT NULL DEFAULT ''     COMMENT '邮件码',
`create_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '发送时间',
`expire_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '过期时间',
PRIMARY KEY (`id`),
KEY (`scene`),
KEY (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='邮件码';


# 写入邮件码
$scene = '';
$email = '';
$code  = '';
$bool  = \mailer\Code::set($scene, $email, $code);


# 获取发送倒计时（秒）
$scene  = '';
$email  = '';
$second = \mailer\Code::countdown($scene, $email);


# 验证邮件码 | 前置验证
$scene = '';
$email = '';
$res   = \mailer\Code::checkPre($scene, $email);
if (1 == $res[0]) {
    // 验证失败
    return [1,$res[1]];
}


# 验证邮件码 | 结果验证
$scene = '';
$email = '';
$code  = '';
$res   = \mailer\Code::checkRes($scene, $email, $code);
if (0 == $res[0]) {
    # 删除邮件码记录
    \mailer\Code::del($scene, $email);
} else {
    return [1,$res[1]];
}


# 删除邮件码记录
$scene = '';
$email = '';
\mailer\Code::del($scene, $email);
*/

namespace mailer;
use think\facade\Db;

class Code
{
    // 间隔时间
    const INTERVAL = 60;

    // 过期时间
    const EXPIRE   = 1800;

    // 写入邮件码
    public static function set($scene, $email, $code)
    {
        $where   = [];
        $where[] = ['scene','=',$scene];
        $where[] = ['email','=',$email];
        $ret = Db::name('mail_code')->where($where)->find();
        if ( is_null($ret) ) {
            // 新增
            $data['scene']       = $scene;
            $data['email']       = $email;
            $data['code']        = $code;
            $data['create_time'] = time();
            $data['expire_time'] = time() + self::EXPIRE;
            $num = Db::name('mail_code')->insert($data);
        } else {
            // 更新
            $data['code']        = $code;
            $data['create_time'] = time();
            $data['expire_time'] = time() + self::EXPIRE;
            $num = Db::name('mail_code')->where($where)->update($data);
        }
        return ($num > 0) ? true : false;
    }

    // 获取发送倒计时（秒）
    public static function countdown($scene, $email)
    {
        $ret = Db::name('mail_code')->where('scene',$scene)->where('email',$email)->find();
        if ( is_null($ret) ) {
            return 0;
        } else {
            $sec = ($ret['create_time'] + self::INTERVAL) - time();
            return ($sec >= 0) ? $sec : 0;
        }
    }

    // 验证邮件码 | 前置验证
    public static function checkPre($scene, $email)
    {
        $where[] = ['scene','=',$scene];
        $where[] = ['email','=',$email];
        $ret = Db::name('mail_code')->where($where)->find();
        if ( !is_null($ret) ) {
            if (time() - $ret['create_time'] < self::INTERVAL) return [1,'The sending interval is not less than ' . self::INTERVAL . 's'];
        }
        return [0,'Verification succeeded'];
    }

    // 验证邮件码 | 结果验证
    public static function checkRes($scene, $email, $code)
    {
        $where[] = ['scene','=',$scene];
        $where[] = ['email','=',$email];
        $ret = Db::name('mail_code')->where($where)->find();
        if ( is_null($ret) )                return [1,'Code not sent'];
        if ( time() > $ret['expire_time'] ) return [1,'Code has expired'];
        if ( $ret['code'] != $code )        return [1,'Code is incorrect'];
        return [0,'Verification succeeded'];
    }

    // 删除邮件码记录
    public static function del($scene, $email)
    {
        $where[] = ['scene','=',$scene];
        $where[] = ['email','=',$email];
        $num = Db::name('mail_code')->where($where)->delete();
        return ($num > 0) ? [0,'Successfully deleted'] : [1,'Failed to delete'];
    }
}