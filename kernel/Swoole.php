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
        # 创建server
        $server = new \swoole_http_server($host,$port);
        # 设置运行参数
        $server->set([
            'worker_num' => 20,
            'daemonize' => 0,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
            'upload_tmp_dir' => UPLOAD_TMP_DIR,
        ]);
        # 判断是否要设置参数
        if(count($param) > 0){
            $server -> set($param);
        }
        # 监听请求
        $server -> on('request',[&$this,'onRequest']);
        # 启动server
        $server -> start();
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