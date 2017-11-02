<?php
namespace Kernel;
/**
 * Swoole Class
 * Class Swoole
 * @package Kernel
 */
class Swoole
{
    /**
     * 定义接口
     * @var null | object
     */
    protected static $interface = null;
    /**
     * Server
     * @var null | object
     */
    protected static $server = null;
    /**
     * 静态资源目录
     * @var null | string
     */
    protected static $documentRoot = null;
    /**
     * 要设置的参数
     * @var array
     */
    protected static $param = [
        'worker_num' => 20,
        'daemonize' => 0,
        'max_request' => 10000,
        'dispatch_mode' => 2,
        'debug_mode'=> 1,
        'log_file'=> CACHE_LOG.'swoole.log',
        'upload_tmp_dir'=> UPLOAD_TMP_DIR,
    ];
    /**
     * 获取接口
     */
    public static function get_interface()
    {
        if(self::$interface===null){
//            echo "实例化Swoole类\n";
            self::$interface = new self(...func_get_args());
        }
        return self::$interface;
    }

    /**
     * Swoole类 构造方法
     * @param string $host
     * @param int $port
     * @param array $param
     */
    protected function __construct($host = '0.0.0.0',$port=9501,$param = [])
    {
        # 目录常量检测
        \Kernel\Kernel::init();
        # 加载ENV配置
        \Kernel\Kernel::load_env();
        # 注册类映射方法
        spl_autoload_register('Kernel\Kernel::auto_load');
        # 设置时区
        date_default_timezone_set(Config::get('sys','default_timezone'));
        # 创建server
        self::$server = new \swoole_http_server($host,$port);
        # 判断是否设置了静态资源目录
        if(self::getDocumentRoot() != null){
            self::$param['enable_static_handler'] = true;
            self::$param['document_root'] = self::$documentRoot;
        }
        # 判断是否要设置自定义参数
        if(count($param) > 0){
            foreach ($param as $key=>$item){
                self::$param[$key] = $item;
            }
        }
        # 设置参数
        self::$server -> set(self::$param);
        # 监听请求
        self::$server -> on('request',[&$this,'onRequest']);
        # 启动server
        self::$server -> start();
    }

    /**
     * 设置静态文件目录
     * @param $finder
     */
    public static function setDocumentRoot($finder)
    {
        if(is_dir($finder)){
            self::$documentRoot = $finder;
        }else{
            var_dump("静态文件目录不存在");
        }
    }

    /**
     * 获取静态资源目录
     * @return mixed
     */
    public static function getDocumentRoot()
    {
        return self::$documentRoot;
    }

    /**
     * 请求处理
     */
    public function onRequest($request, $response)
    {
        # 判断是否下载了composer包
        if ( file_exists(ROOT_PATH.'vendor'.DIRECTORY_SEPARATOR.'autoload.php') ) {
            # 引用Composer自动加载规则
            require_once(ROOT_PATH.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
        }else{
            # 发送状态码
            $response->status(200);
            # 发送内容
            $response -> end('请在项目根目录执行:composer install');
        }
//        echo $request -> server['request_uri']."\n";
        # 获取GET 数据
        $_GET = $request -> get;
        # 获取SERVER数据
        $_SERVER = $request -> server;
        # 获取POST 数据
        $_POST = $request -> post;
        # 获取COOKIE 数据
        $_COOKIE = $request -> cookie;
        # 获取上传的文件数据
        $_FILES = $request -> files;
        # 设置session_id
        session_id($request->cookie[Config::get('sys','session_name')]);
        # 设置session的回话到cookie里
        $response -> cookie(
            Config::get('sys','session_name'),
            session_id($request->cookie[Config::get('sys','session_name')]),
            Config::get('sys','session_lifetime'),
            Config::get('sys','session_cookie_path'),
            Config::get('sys','session_range'));
        # 开启应用
        Kernel::start($request,$response);
    }
}