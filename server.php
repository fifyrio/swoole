<?php
# 验证PHP版本
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义项目根目录
define('ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR);
# 定义是否为SWOOLE
define('IS_SWOOLE', true);
# 开启调试模式
define('DE_BUG',true);
# 引入处理类
require( ROOT_PATH.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
# 设置web 静态资源目录
Kernel\Swoole::setDocumentRoot(ROOT_PATH.'public/');
# 获取接口
$swoole = Kernel\Swoole::get_interface($server);

