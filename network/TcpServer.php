<?php
namespace Network;
/**
 * TCP 服务器
 * Class TcpServer
 * @package Network
 */
class TcpServer
{
    /**
     * 服务
     * @var null | object
     */
    protected $server = null;

    /**
     * 构造方法
     * TcpServer constructor.
     * @param $host
     * @param $port
     * @param $mode
     * @param $sock_type
     */
    public function __construct($host,$port,$mode = SWOOLE_PROCESS,$sock_type = SWOOLE_SOCK_TCP)
    {
        $this -> server = new \swoole_server($host,$port,$mode,$sock_type);
    }

    /**
     * 监听事件
     * @param string $event
     * @param \Closure $func
     * @return mixed
     */
    public function on($event,$func)
    {
        return $this -> server -> on(...func_get_args());
    }

    /**
     * 启动服务
     * @return mixed
     */
    public function start()
    {
        return $this -> server -> start();
    }

    /**
     * 当连接时
     * @param \Closure $func($server,$fd)
     * @return mixed
     */
    public function connect($func)
    {
        return $this -> on('connect',$func);
    }

    /**
     * 接收到数据时
     * @param \Closure $func($server,$fd,$from_id,$data)
     * @return mixed
     */
    public function receive($func)
    {
        return $this -> on('receive',$func);
    }

    /**
     * 当tcp 连接关闭时
     * @param \Closure $func($server,$fd)
     * @return mixed
     */
    public function close($func)
    {
        return $this -> on('close',$func);
    }
}