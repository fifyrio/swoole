<?php
namespace Network;
use Kernel\Config;
use Kernel\Kernel;
/**
 * Class WebServer
 * @package Network
 */
class WebServer
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
    ];
    /**
     * 监听主机
     * @var string
     */
    protected static $host = '0.0.0.0';
    /**
     * 监听端口
     * @var int
     */
    protected static $port = 9501;

    /**
     * WebServer 类 构造方法
     * @param string $host
     * @param int $port
     * @param array $param
     */
    public function __construct($host = '0.0.0.0',$port=9501,$param = [])
    {
        # 获取监听的主机
        self::$host = $host;
        # 获取监听的端口
        self::$port = $port;
        # 设置PHP运行时 最大内存
        ini_set('memory_limit', '128M');
        # 核心组件初始化
        Kernel::init();
        # 创建server
        self::$server = new \swoole_http_server(self::$host,self::$port);
        # 判断是否设置了静态资源目录
        if(self::getDocumentRoot() != null){
            self::$param['enable_static_handler'] = true;
            self::$param['document_root'] = self::$documentRoot;
        }
        # 定义日志文件
        self::$param['log_file'] = CACHE_LOG.'swoole.log';
        # 定义上传临时文件夹
        self::$param['upload_tmp_dir'] = UPLOAD_TMP_DIR;
        # 判断是否要设置自定义参数
        if(count($param) > 0){
            foreach ($param as $key=>$item){
                self::$param[$key] = $item;
            }
        }
        # 设置参数
        self::$server -> set(self::$param);
        # 启动时执行
        self::$server -> on("start", [&$this,'onStart']);
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
     * 启动时执行
     */
    public function onStart()
    {
        echo "Swoole http server is started at http://".self::$host.":".self::$port."\n";
    }
    /**
     * 请求处理
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
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
