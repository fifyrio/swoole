<?php
namespace Kernel;
use Itxiao6\Session\Session;
use Service\DB;
use Service\Exception;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Itxiao6\Route\Route;
use Service\Http;
use Itxiao6\Route\Resources;
use Itxiao6\DebugBar\DebugBar;
use Itxiao6\DebugBar\DataCollector\ExceptionsCollector;
use Itxiao6\DebugBar\DataCollector\MessagesCollector;
use Itxiao6\DebugBar\DataCollector\PhpInfoCollector;
use Itxiao6\DebugBar\DataCollector\RequestDataCollector;
/**
* 框架核心类
*/
class Kernel
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

    /**
     * 加载环境变量
     */
    public static function load_env()
    {
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
    }
    /**
     * 常亮和目录检查
     */
    public static function init()
    {
        # 判断缓存主目录是否存在
        if(!is_dir(ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR)){
            # 递归创建目录
            mkdir(ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR,0777,true);
        }
        if(!(defined('CACHE_DATA') && CACHE_DATA != '')){
            # 数据缓存目录
            define('CACHE_DATA',ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR);
        }
        # 检查目录是否存在
        if(!is_dir(CACHE_DATA)){
            # 递归创建目录
            mkdir(CACHE_DATA,0777,true);
        }
        if(!(defined('CLASS_PATH') && CLASS_PATH != '')){
            # 类映射缓存目录
            define('CLASS_PATH',ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR);
        }
        # 检查目录是否存在
        if(!is_dir(CLASS_PATH)){
            # 递归创建目录
            mkdir(CLASS_PATH,0777,true);
        }
        if(!(defined('CACHE_LOG') && CACHE_LOG != '')){
            # 日志文件缓存路径
            define('CACHE_LOG',ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR);
        }
        # 检查目录是否存在
        if(!is_dir(CACHE_LOG)){
            # 递归创建目录
            mkdir(CACHE_LOG,0777,true);
        }
        if(!(defined('CACHE_SESSION') && CACHE_SESSION != '')){
            # 会话文件缓存路径
            define('CACHE_SESSION',ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR.'session'.DIRECTORY_SEPARATOR);
        }
        # 检查目录是否存在
        if(!is_dir(CACHE_SESSION)){
            # 递归创建目录
            mkdir(CACHE_SESSION,0777,true);
        }
        # 判断是否定义了临时上传目录
        if(defined('UPLOAD_TMP_DIR')){
            # 检查目录是否存在
            if(!is_dir(UPLOAD_TMP_DIR)){
                # 递归创建目录
                mkdir(UPLOAD_TMP_DIR,0777,true);
            }
        }

        if(!(defined('CACHE_VIEW') && CACHE_VIEW != '')){
            # 模板编译缓存目录
            define('CACHE_VIEW',ROOT_PATH.'runtime'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR);
        }
        # 检查目录是否存在
        if(!is_dir(CACHE_VIEW)){
            # 递归创建目录
            mkdir(CACHE_VIEW,0777,true);
        }
    }

    /**
     * 启动框架
     */
    public static function start($request = null,$response = null)
    {
        # 判断是否开启了debugbar
        if(Config::get('sys','debugbar')) {
            # 定义全局变量
            global $debugbar;
            global $debugbarRenderer;
            global $database;

            # 启动DEBUGBAR
            $debugbar = new DebugBar();
            $debugbar->addCollector(new PhpInfoCollector());
            $debugbar->addCollector(new MessagesCollector('Time'));
            $debugbar->addCollector(new MessagesCollector('Request'));
            $debugbar->addCollector(new MessagesCollector('Session'));
            $debugbar->addCollector(new MessagesCollector('Database'));
            $debugbar->addCollector(new MessagesCollector('Application'));
            $debugbar->addCollector(new MessagesCollector('View'));
            $debugbar->addCollector(new RequestDataCollector());

            $debugbarRenderer = $debugbar->getJavascriptRenderer();
        }
        # 启动会话
        Session::session_start();

        # 设置请求
        Http::set_request($request);
        # 设置响应
        Http::set_response($response);

        # 设置url 分隔符
        Route::set_key_word(Config::get('sys','url_split'));
        try{
            # 加载路由
            Route::init(function($app,$controller,$action){
                $view_path = Config::get('sys','view_path');
                if(!in_array(ROOT_PATH.'app'.DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.'View',$view_path)){
                    $view_path[] = ROOT_PATH.'app'.DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.'View';
                    Config::set('sys',
                        $view_path
                        ,'view_path');
                }
            });
        }catch (\Exception $exception){
            # 页面找不到
            Http::send_http_status(404);
        }
    }
}