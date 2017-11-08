<?php
namespace Service;
use Itxiao6\Database\Capsule\Manager;
use Illuminate\Container\Container;
use Kernel\Config;

/**
 * 数据库类
 * Class DB
 * @package Service
 */
class DB extends Manager
{
    /**
     * 定义数据库状态
     * @var bool
     */
    protected static $databases_status = false;
    /**
     * 数据库链接
     */
    public static function connection_databases(){
        # 判断数据库是否已经连接
        if ( self::$databases_status === false) {
            # 连接数据库
            $database = new Manager;
            # 载入数据库配置
            $database->addConnection(Config::get('database'));
            # 设置全局静态可访问
            $database->setAsGlobal();
            # 启动Eloquent
            $database -> bootEloquent();
            # 判断是否开启LOG日志
            if(Config::get('sys','database_log')){
                Manager::connection()->enableQueryLog();
            }
            # 定义数据库已经连接
            self::$databases_status = true;
        }
    }

    /**
     * 获取PDO 实例
     * @return \PDO
     */
    public static function GET_PDO()
    {
        self::connection_databases();
        return static::connection() -> getPdo();
    }

    /**
     * 获取数据库sql Log
     * @return bool
     * @throws \Exception
     */
    public static function DB_LOG(){
        # 数据库是否连接
        if(self::$databases_status){
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
        self::connection_databases();
        return static::connection()->$method(...$parameters);
    }

    /**
     * 创建一个 数据库容器
     * DB constructor.
     * @param Container|null $container
     */
    public function __construct(Container $container = null)
    {
        self::connection_databases();
        $this->setupContainer($container ?: new Container);

        // Once we have the container setup, we will setup the default configuration
        // options in the container "config" binding. This will make the database
        // manager work correctly out of the box without extreme configuration.
        $this->setupDefaultConfiguration();

        $this->setupManager();
    }
    public static function table($table, $connection = null)
    {
        self::connection_databases();
        return parent::table($table, $connection);
    }
    public static function schema($connection = null)
    {
        self::connection_databases();
        return parent::schema($connection);
    }
}