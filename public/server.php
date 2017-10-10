<?php
# Swoole
# 监听主机
$host = '0.0.0.0';
# 端口
$port = 9501;
# 是否多线程
$mode = SWOOLE_PROCESS;
# 方式
$sock_type = SWOOLE_SOCK_TCP;

//$server = swoole_server($host,$port,$mode,$sock_type);
$server = swoole_server($host,$port);

$server -> on('connect',function($server,$fd){
    var_dump($server);
    var_dump($fd);
    echo '建立链接成功'."\n";
});

$server -> on('receive',function($server,$fd,$form_id,$data){
    echo '接收到数据'."\n";
});

$server -> on('close',function($server,$fd){
    echo '链接关闭';
});

$server -> start();