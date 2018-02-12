<?php
namespace Container;
use Kernel\Config;
/**
 * 依赖注入容器
 * Class IOC
 */
class IOC
{
    /**
     * 类影射数组
     * @var array
     */
    protected static $class = [];
    /**
     * 类的映射
     * @param $class
     */
    public static function auto_load($class){
        if(count(self::$class)==0){
            # 加载配置文件
            self::$class = Config::get('class');
        }
        # 判断类是否存在
        if(isset(self::$class[$class])){
            # 获取类文件名
            $class_name = str_replace('\\','_',CLASS_PATH.self::$class[$class].'.php');
            # 判断缓存文件是否存在
            if(!file_exists($class_name)){
                # 写入文件
                file_put_contents($class_name,'<?php class '.$class.' extends '.self::$class[$class].'{ }');
            }
            # 引入映射类
            require($class_name);
        }
    }
}