<?php
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义项目根目录
define('ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
# 定义是否为SWOOLE
define('IS_SWOOLE', true);
# 开启调试模式
define('DE_BUG',true);

$http = new swoole_http_server("0.0.0.0", 9501);
$http->on('request', function ($request, $response) {
    # 引入入口文件
    require_once( ROOT_PATH.'kernel'.DIRECTORY_SEPARATOR.'Kernel.php');
    # 启动
    Kernel\Kernel::start($request,$response);
});
$http->start();