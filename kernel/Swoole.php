<?php
namespace Kernel;
use Network\TcpServer;
use Network\UdpServer;
use Network\WebServer;
use Network\WebSocket;

/**
 * Swoole Class
 * Class Swoole
 * @package Kernel
 */
class Swoole
{
    /**
     * 启动 web server
     * @return WebServer
     */
    public static function web_server_start()
    {
        return new WebServer(...func_get_args());
    }

    /**
     * 启动 tcp server
     * @return TcpServer
     */
    public static function tcp_server_start()
    {
        return new TcpServer(...func_get_args());
    }

    /**
     * 启动 udp server
     * @return UdpServer
     */
    public static function udp_server_start()
    {
        return new UdpServer(...func_get_args());
    }

    /**
     * 启动 web socket server
     * @return SocketServer
     */
    public static function socket_server_start()
    {
        return new WebSocket(...func_get_args());
    }
}