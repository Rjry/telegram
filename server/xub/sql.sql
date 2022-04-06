-- 管理员
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
`id`           int(11)       NOT NULL AUTO_INCREMENT COMMENT '主键',
`uuid`         char(32)      NOT NULL                COMMENT 'UUID',
`role`         char(12)      NOT NULL                COMMENT '角色',
`username`     char(32)      NOT NULL                COMMENT '帐号',
`password`     char(32)      NOT NULL DEFAULT ''     COMMENT '密码',
`create_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '创建时间',
`update_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '更新时间',
`delete_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '禁用时间',
PRIMARY KEY (`id`),
UNIQUE KEY (`uuid`),
UNIQUE KEY (`username`),
KEY (`delete_time`)
) ENGINE=InnoDB AUTO_INCREMENT=100001 DEFAULT CHARSET=utf8mb4 COMMENT='管理员';

INSERT INTO `manager` (`uuid`, `role`, `username`, `password`, `create_time`) VALUES (
    '2e6059ae89604e7d0b294488be8ef12e',
    'manager',
    'admin',
    md5('admin'),
    unix_timestamp(now())
);


-- 管理员登录日志
DROP TABLE IF EXISTS `manager_login_log`;
CREATE TABLE `manager_login_log` (
`id`           int(11)       NOT NULL AUTO_INCREMENT COMMENT '主键',
`uuid`         char(32)      NOT NULL                COMMENT 'UUID',
`role`         char(32)      NOT NULL                COMMENT '角色',
`username`     char(32)      NOT NULL                COMMENT '帐号',
`ip`           char(25)      NOT NULL DEFAULT ''     COMMENT 'IP地址',
`ip_addr`      char(40)      NOT NULL DEFAULT ''     COMMENT 'IP定位',
`create_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '登录时间',
PRIMARY KEY (`id`),
KEY (`uuid`),
KEY (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='管理员登录日志';






-- 接口日志
DROP TABLE IF EXISTS `api_log`;
CREATE TABLE `api_log` (
`id`           int(11)       NOT NULL AUTO_INCREMENT COMMENT '主键',
`method`       char(10)      NOT NULL DEFAULT ''     COMMENT '请求方法',
`path`         char(80)      NOT NULL DEFAULT ''     COMMENT '请求接口',
`params`       text                                  COMMENT '请求参数',
`result`       text                                  COMMENT '返回结果',
`req_ip`       char(25)      NOT NULL DEFAULT ''     COMMENT '来源地址',
`req_at`       int(4)        NOT NULL DEFAULT 0      COMMENT '响应时间',
`create_time`  int(10)       NOT NULL DEFAULT 0      COMMENT '创建时间',
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='接口日志';



