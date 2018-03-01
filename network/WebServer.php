<?php
namespace Network;
use Kernel\Config;
use Kernel\Kernel;
use Swoole\Http\Server;
use Swoole\Process;

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
    protected $server = null;
    /**
     * 静态资源目录
     * @var null | string
     */
    protected $documentRoot = ROOT_PATH.'public';
    /**
     * 要设置的参数
     * @var array
     */
    protected $param = [
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
    protected $host = '0.0.0.0';
    /**
     * 监听端口
     * @var int
     */
    protected $port = 9501;
    /**
     * 回调方法
     * @var array
     */
    protected $callback = [];

    /**
     * WebServer 类 构造方法
     * WebServer constructor.
     * @param string $host
     * @param int $port
     * @param array $param
     * @param bool $is_start
     */
    public function __construct($host = '0.0.0.0',$port=9501,$param = [],$is_start = true)
    {
        if(!$is_start){
            return;
        }
        # 设置进程永不过期
        set_time_limit(0);
        # 设置PHP运行时 最大内存
        ini_set('memory_limit', '128M');
        # 创建server
        $this -> server = new \swoole_http_server($host,$port);
        # 设置静态资源目录
        $this  -> param['enable_static_handler'] = true;
        $this  -> param['document_root'] = $this -> documentRoot;
        # 定义日志文件
        $this -> param['log_file'] = ROOT_PATH.'runtime'.DS.'log'.DS.'swoole.log';
        # 定义上传临时文件夹
        $this -> param['upload_tmp_dir'] = ROOT_PATH.'runtime'.DS.'upload';
        # 判断是否要设置自定义参数
        if(count($param) > 0){
            foreach ($param as $key=>$item){
                $this -> param[$key] = $item;
            }
        }
        # 启动时执行
        $this -> server -> on("start", [&$this,'onStart']);
        # 当服务结束时执行
        $this -> server -> on("close", [&$this,'onClose']);
        # 当服务关闭的时候
        $this -> server -> on("shutdown", [&$this,'onShutdown']);
        # 监听请求
        $this -> server -> on('request',[&$this,'onRequest']);

    }

    /**
     * 设置参数
     * @param $key
     * @param null $value
     * @return $this
     */
    public function set_callback($key,$value = null)
    {
        if(is_array($key) && count($key) > 0 && $value === null){
            foreach ($key as $k=>$v){
                $this -> set_callback($k,$v);
            }
        }else{
            $this -> callback[$key] = $value;
        }
        return $this;
    }

    /**
     * 启动server
     * @param bool $is_daemon
     * @return bool
     */
    public function start($is_daemon = false)
    {
        $this -> param['daemonize'] = $is_daemon;
        # 设置参数
        $this -> server -> set($this -> param);
        # 启动server
        return $this -> server -> start();
    }

    /**
     * 停止server
     * @param $port
     * @return bool
     */
    public function stop($port)
    {
        echo "Http Server stop at ".date('Y-m-d H:i:s')."\n";
        Process::kill(file_get_contents(ROOT_PATH.'runtime'.DS.'process'.DS.'web_server_'.$port));
        unlink(file_get_contents(ROOT_PATH.'runtime'.DS.'process'.DS.'web_server_'.$port));
        return true;
    }

    /**
     * 启动时执行
     */
    public function onStart(Server $server)
    {
        # 检查目录
        if(!is_dir(ROOT_PATH.'runtime'.DS.'process')){
            mkdir(ROOT_PATH.'runtime'.DS.'process');
        }
        # 写入pid 文件
        file_put_contents(ROOT_PATH.'runtime'.DS.'process'.DS.'web_server_'.$this -> port,$server -> master_pid);
        # 判断是否定义了 参数
        if(isset($this -> callback['start']) && $this -> callback['start'] != null){
            $this -> callback['start'](...func_get_args());
        }
    }
    /**
     * 当服务结束的时候
     */
    public function onClose()
    {
        if(isset($this -> callback['close']) && $this -> callback['close'] != null){
            $this -> callback['close'](...func_get_args());
        }
    }

    /**
     * 当服务关闭的时候
     */
    public function onShutdown()
    {
        if(isset($this -> callback['shutdown']) && $this -> callback['shutdown'] != null){
            $this -> callback['shutdown'](...func_get_args());
        }
    }
    /**
     * 请求处理
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        if(isset($this -> callback['request']) && $this -> callback['request'] != null){
            $this -> callback['request']($request, $response);
        }
    }
}
