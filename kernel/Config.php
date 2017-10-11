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
     * 驱动 file | databases
     * @var string
     */
    protected static $driver = 'file';
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
            if(self::$config == 'file'){
                # 判断配置文件是否存在
                if(file_exists(ROOT_PATH.'config/'.$type.'.php')){
                    self::$config[$type] = require(ROOT_PATH.'config/'.$type.'.php');
                }else{
                    return false;
                }
            }else{
                # 判断配置信息是否存在
                if($data = \App\Model\Config::where(['type'=>$type]) -> pluck('value','key')){
                    self::$config[$type] = $data -> toArray();
                }else{
                    return false;
                }
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
     * @param string $type
     * @param array | string $value
     * @param null | string $key
     * @return mixed
     */
    public static function set($type,$value,$key=null)
    {
        if($key===null){
            if(self::$driver=='file'){
                return self::$config[$type] = $value;
            }else{
                return \App\Model\Config::where(['type'=>$type]) -> update($value);
            }
        }else{
            if(self::$driver=='file'){
                return self::$config[$type][$key] = $value;
            }else{
                return \App\Model\Config::where(['type'=>$type,'key'=>$key]) -> update(['value'=>$value]);
            }
        }
    }
}