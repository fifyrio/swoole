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
# 获取接口
Kernel\Swoole::web_server_start('0.0.0.0',9502);

