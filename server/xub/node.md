


















































### Git
```
# 全局配置
$ git config --global user.name "rjry"
$ git config --global user.email "rjry123@163.com"

# 记住帐密
$ git config --global credential.helper store

# 创建部署密钥
$ ssh-keygen -t rsa -C "rjry123@163.com"
$ cd ~/.ssh
$ cat id_rsa.pub
```

### PhpRedisAdmin [Ip + Port: 6380]
```
$ git clone https://gitee.com/rjry/phpredisadmin.git phpredisadmin

OR

$ git clone https://github.com/ErikDubbelboer/phpRedisAdmin.git phpredisadmin
$ cd phpredisadmin
$ git clone https://github.com/nrk/predis.git vendor

$ cd /phpredisadmin/includes/
$ cp config.sample.inc.php config.inc.php

// Uncomment to enable HTTP authentication
'login' => array(
    'admin' => array(
      'password' => 'xxxxxx',
    ),
),
```

### .env
```
APP_DEBUG = false

[APP]
NAME = SACloud
HOST = http://localhost:8000

[DATABASE]
TYPE     = mysql
HOSTNAME = 127.0.0.1
DATABASE = trcloud
USERNAME = trcloud
PASSWORD = 
HOSTPORT = 3306
CHARSET  = utf8mb4
DEBUG    = true

[CACHE]
DRIVER   = redis

[LANG]
default_lang = zh-cn
```

### env.json
```
{
    "title": "TRCloud",
    
    "api_url": "http://xxx.xxx.xxx.xxx/api",
    
    "websocket_url": "ws://xxx.xxx.xxx.xxx/socket"
}
```


### WebSocket
```
1.在 server 上方添加以下代码:

map $http_upgrade $connection_upgrade {
    default upgrade;
    '' close;
}
upstream websocket {
    server 127.0.0.1:9527;
}

2.搜索 #SSL-END 在这串注释后面加上以下代码：

# Nginx代理WSS
# wss://xxx.xxx/socket
location /socket {
    proxy_pass http://127.0.0.1:9527;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "Upgrade";
}
```