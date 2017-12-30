<?php
namespace Network;
/**
 * 客户端
 * Class Client
 * @package Network
 */
class Client
{
    /**
     * 客户端句柄
     * @var null|object
     */
    protected $client = null;

    /**
     * 构造方法
     * Client constructor.
     * @param $type
     */
    public function __construct($type = SWOOLE_SOCK_TCP)
    {
        $this -> client = new \swoole_client($type);
    }


}