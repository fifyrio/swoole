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
$tcp_server = \Kernel\Swoole::tcp_server_start('0.0.0.0',9501);
# 当tcp 连接进入时
$tcp_server -> connect(function($server,$fd){
    echo "有客户端连接";
    echo $fd;
    echo "\n";
});
# 当接收到 数据时
$tcp_server -> receive(function($server,$fd,$from_id,$data){
    echo "接收到数据:";
    echo $data;
    echo "\n";
});
# 当tcp 连接被关闭的时候
$tcp_server -> close(function($server,$fd){
    echo "连接关闭";
    echo $fd;
    echo "\n";
});
$tcp_server -> start();

# 操作
switch ($argv[1]){
    case 'start':

        break;
    case 'stop':

        break;
    case 'reload':

        break;
    default:
        // 输出帮助信息
        break;
}

//var_dump($argv);
//die();

# 获取接口
//Kernel\Swoole::web_server_start('0.0.0.0',9501);

