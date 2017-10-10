<?php
namespace Kernel;
/**
 * 配置类
 * Class Config
 * @package Kernel
 */
class Config
{
    /**
     * 配置
     * @var array
     */
    protected static $config = [];
    /**
     * 读取配置
     * @param string $type
     * @param null $key
     * @return bool
     */
    public static function get($type='app',$key=null)
    {
        # 判断配置文件是否加载过
        if(!isset(self::$config[$type])){
            # 判断配置文件是否存在
            if(file_exists(ROOT_PATH.'config/'.$type.'.php')){
                self::$config[$type] = require(ROOT_PATH.'config/'.$type.'.php');
            }else{
                return false;
            }
        }
        # 是否读取全部配置
        if($key===null){
            # 返回要取得的值
            return self::$config[$type];
        }else{
            # 返回要取得的值
            return self::$config[$type][$key];
        }
    }
    /**
     * 设置配置
     * @param $type
     * @param $value
     * @param null $key
     * @return mixed
     */
    public static function set($type,$value,$key=null)
    {
        if($key===null){
            self::$config[$type] = $value;
        }else{
            self::$config[$type][$key] = $value;
        }
        return $value;
    }
}