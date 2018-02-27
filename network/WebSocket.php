<?php
namespace Network;

/**
 * Class WebSocket
 * @package Network
 */
class WebSocket
{
    /**
     * 绑定的ip
     * @var string
     */
    protected $host = '0.0.0.0';
    /**
     * 端口
     * @var int|null
     */
    protected $port = 9502;
    /**
     * 客户端
     * @var array
     */
    protected $client = [];
    /**
     * WebSocket constructor.
     */
    public function __construct($host = null,$port = null)
    {
    }
}