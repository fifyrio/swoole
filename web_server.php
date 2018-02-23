<?php
# 框架初始化
require_once('./init.php');
# 创建一个web服务
Kernel\Swoole::web_server_start('0.0.0.0',9501)
    -> set_callback('request',function($request,$response){
        \App\Http\Kernel::start(\Container\Request::getInterface($request),\Container\Response::getInterface($response));
    }) -> set_callback('start',function($server){
        echo "server start，".$server -> host.":".$server -> port."。\n";
    }) -> start();