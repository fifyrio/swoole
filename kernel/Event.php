<?php
namespace Kernel;
use Itxiao6\Database\Capsule\Manager;
use Itxiao6\Session\Session;
use Service\DB;

/**
 * 系统事件
 * Class Event
 * @package Kernel
 */
class Event
{
    /**
     * 定义数据库状态
     * @var bool
     */
    protected static $databases_status = false;
    /**
     * web 路由入口
     * @param $appName
     * @param $controllerName
     * @param $actionName
     * @param $request
     * @param $response
     * @param $session
     */
    public static function web_route($appName,$controllerName,$actionName,$request,$response,$session)
    {
        sprintf('\App\Http\%s\Controller\%s',$appName,$controllerName)::getInterface($appName,$controllerName,$actionName,$request,$response,$session);
    }

    /**
     * 启动会话
     * @param $request
     * @param $response
     * @return mixed
     * @throws \Exception
     */
    public static function session($request,$response)
    {
        /**
         * REDIS SESSION
         */
//        $redis = new \Redis();
//        $redis -> connect('127.0.0.1',6379);
//        # 启动会话
//        $session = Session::getInterface($request,$response) -> driver('Redis') -> start($redis);
        /**
         * Local File SESSION
         */
        if(!is_dir(ROOT_PATH.'runtime'.DS.'session'.DS)){
            mkdir(ROOT_PATH.'runtime'.DS.'session'.DS);
        }
        $session = Session::getInterface($request,$response) -> start(ROOT_PATH.'runtime'.DS.'session'.DS);
        /**
         * MySql Session
         */
//        $pdo = new \PDO("mysql:host=47.104.85.153;dbname=new_baihua",'new_baihua','new_baihua2017');
//        $session = Session::getInterface($request,$response) -> driver('MySql') -> start($pdo,'session_table');
        return $session;
    }

    /**
     * 数据库连接
     */
    public static function databases_connection()
    {
        # 判断数据库是否已经连接
        if (!self::get_databases_status()) {

            # 连接数据库
            $database = DB::getInterface();

            # 载入数据库配置
            $database -> addConnection(Config::get('database'));

            # 设置全局静态可访问
            $database -> setAsGlobal();
            # 启动Eloquent
            $database -> bootEloquent();
            # 判断是否开启LOG日志
            if(Config::get('sys','database_log')){
                DB::connection()->enableQueryLog();
            }

            # 定义数据库已经连接
            self::$databases_status = true;
        }
    }

    /**
     * 获取数据库连接状态
     * @return bool
     */
    public static function get_databases_status()
    {
        return self::$databases_status;
    }

}