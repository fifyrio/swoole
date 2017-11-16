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
     * Server
     * @var null | object
     */
    protected static $server = null;
    /**
     * 静态资源目录
     * @var null | string
     */
    protected static $documentRoot = ROOT_PATH.'public';
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
        # 设置主进程永不过期
        set_time_limit(0);
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
        # 设置静态资源目录
        self::$param['enable_static_handler'] = true;
        self::$param['document_root'] = self::$documentRoot;
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
        # 当服务结束时执行
        self::$server -> on("close", [&$this,'onClose']);
        # 当服务关闭的时候
        self::$server -> on("shutdown", [&$this,'onShutdown']);
        # 监听请求
        self::$server -> on('request',[&$this,'onRequest']);
        # 启动server
        self::$server -> start();
    }

    /**
     * 启动时执行
     */
    public function onStart()
    {
        echo "Swoole http server is started at http://".self::$host.":".self::$port."\n";
    }
    # 当服务结束的时候
    public function onClose()
    {
        echo "Swoole http server Close";
    }
    # 当服务关闭的时候
    public function onShutdown()
    {
        echo "Swoole http server Shutdown";
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
        # 开启应用
        Kernel::start($request,$response);
    }
}
