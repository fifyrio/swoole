<?php
namespace Service;
use Itxiao6\Database\Capsule\Manager;
use Illuminate\Container\Container;
use Kernel\Config;
use Kernel\Event;

/**
 * 数据库类
 * Class DB
 * @package Service
 */
class DB extends Manager
{

    /**
     * 获取PDO 实例
     * @return \PDO
     */
    public static function GET_PDO()
    {
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        return static::connection() -> getPdo();
    }

    /**
     * 获取数据库sql Log
     * @return bool
     * @throws \Exception
     */
    public static function DB_LOG(){
        # 数据库是否连接
        if(Event::get_databases_status()){
            return false;
        }
        # 判断是否开启了DB_log
        if(Config::get('sys','database_log')){
            return self::getQueryLog();
        }else{
            throw new \Exception('未开启DB_log');
        }
    }
    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        return static::connection()->$method(...$parameters);
    }

    /**
     * 创建一个 数据库容器
     * DB constructor.
     * @param Container|null $container
     */
    public function __construct(Container $container = null)
    {
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        $this->setupContainer($container ?: new Container);

        // Once we have the container setup, we will setup the default configuration
        // options in the container "config" binding. This will make the database
        // manager work correctly out of the box without extreme configuration.
        $this->setupDefaultConfiguration();

        $this->setupManager();
    }
    public static function table($table, $connection = null)
    {
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        return parent::table($table, $connection);
    }
    public static function schema($connection = null)
    {
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        return parent::schema($connection);
    }
}