<?php
# 框架初始化
require_once('./init.php');
# 选择操作名
switch ($argv[1]){
    case 'start':
        # 创建一个web服务
        Kernel\Swoole::web_server_start('0.0.0.0',9501)
            -> set_callback('request',function($request,$response){
                \App\Http\Kernel::start(\Container\Request::getInterface($request),\Container\Response::getInterface($response));
            }) -> set_callback('start',function($server){
                echo "server start，".$server -> host.":".$server -> port."。\n";
            }) -> start(isset($argv[2])&&$argv[2]=='--d');
        break;
    case 'stop':
        # 创建一个web服务
        Kernel\Swoole::web_server_start('0.0.0.0',9501,[],false)
            -> stop((isset($argv[2])&&$argv[2]>0)?$argv[2]:9501);
        break;
}


