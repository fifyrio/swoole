<?php
# 验证PHP版本
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义分隔符
define('DS',DIRECTORY_SEPARATOR);
# 定义项目根目录
define('ROOT_PATH',__DIR__.DS);
# 定义是否为SWOOLE
define('IS_SWOOLE', true);
# 开启调试模式
define('DE_BUG',true);
# 引入自动加载类
require( ROOT_PATH.'vendor'.DS.'autoload.php');

$tcp_client = new \swoole_client(SWOOLE_SOCK_TCP);

if (!$tcp_client->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$tcp_client->errCode}\n");
}
for($i=0;$i<=10;$i++){
    $tcp_client->send("hello ".$i);
}
//echo $tcp_client->recv();
$tcp_client->close();