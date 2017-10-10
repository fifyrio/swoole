<?php
# php版本检测
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义项目根目录
define('ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
# 开启调试模式
define('DE_BUG',true);
# 定义默认的协议头
$content_type = 'text/html';
# 引入
require( ROOT_PATH.'kernel'.DIRECTORY_SEPARATOR.'Kernel.php');

# Swoole
$http = new swoole_http_server("0.0.0.0",8081);
$task = 0;
# 监听请求
$http->on('request', function ($request, $response) use (&$task,$content_type){
    $task++;
    echo '请求'.$task."\n";
    # 定义当前使用的是swoole服务
    define('SWOOLE',true);
    $_HEADER = $request -> header;
    $_SERVER = $request -> server;
    foreach ($_SERVER as $key=>$item){
        $_SERVER[strtoupper($key)] = $item;
        unset($_SERVER[$key]);
    }
    # 获取结果
    $result = Kernel\Kernel::start();
    # 设置协议头
    $response->header('Content-Type',$content_type);
    # 获取域名
    $_SERVER['HTTP_HOST'] = $request -> header['host'];
    # 获取cookie
    $_COOKIE = $request -> cookie;
    # 获取get 参数
    $_GET = $request -> get;
    # 获取post 参数
    $_POST = $request -> post;
    # 获取files 数组
    $_FILES_ = $request -> files;
    # 启动程序
    $response->end($result);
});
# 启动web服务器
$http->start();