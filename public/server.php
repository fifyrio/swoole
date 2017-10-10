<?php
# php版本检测
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义项目根目录
define('ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
# 开启调试模式
define('DE_BUG',true);
# 引入
require( ROOT_PATH.'kernel'.DIRECTORY_SEPARATOR.'Kernel.php');

# Swoole
$http = new swoole_http_server("0.0.0.0", 8081);
# 监听请求
$http->on('request', function ($request, $response) {
    $_HEADER = $request -> header;
    $_SERVER = $request -> server;
    foreach ($_SERVER as $key=>$item){
        $_SERVER[strtoupper($key)] = $item;
        unset($_SERVER[$key]);
    }
    $_COOKIE = $request -> cookie;
    $_GET = $request -> get;
    $_POST = $request -> post;
    $_FILES_ = $request -> files;
    # 启动程序
    $response->end(Kernel\Kernel::start());
});
# 启动web服务器
$http->start();