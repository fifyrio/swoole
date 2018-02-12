<?php
# 验证PHP版本
if( PHP_VERSION < 5.6 ){ exit('PHP version <= 5.6'); }
# 定义分隔符
define('DS',DIRECTORY_SEPARATOR);
# 定义项目根目录
define('ROOT_PATH',__DIR__.DS);
# 开启调试模式
define('DE_BUG',true);
# 引入自动加载类
require( ROOT_PATH.'vendor'.DS.'autoload.php');
# 判断环境变量配置文件是否存在
if(file_exists(ROOT_PATH.'.env')){
    # 自定义配置
    $f= fopen(ROOT_PATH.'.env',"r");
}else{
    # 惯例配置
    $f= fopen(ROOT_PATH.'.env.example',"r");
}
# 循环行
while (!feof($f))
{
    $line = fgets($f);
    # 替换单个空格
    $line = preg_replace('! !','',$line);
    # 替换连续空格
    $line = preg_replace('! +!','',$line);
    # 替换制表符或空格
    $line = preg_replace('!\s+!','',$line);
    if((!strstr($line,'#')) && $line!=''){
        # 设置环境变量
        putenv(preg_replace('!\n$!','',$line));
    }
}
# 关闭文件
fclose($f);