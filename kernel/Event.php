<?php
namespace Kernel;
use Itxiao6\Session\Session;

/**
 * 系统事件
 * Class Event
 * @package Kernel
 */
class Event
{
    /**
     * 路由入口
     * @param $appName
     * @param $controllerName
     * @param $actionName
     * @param $request
     * @param $response
     * @param $session
     */
    public static function route($appName,$controllerName,$actionName,$request,$response,$session)
    {
        sprintf('\App\Http\%s\Controller\%s',$appName,$controllerName)::getInterface($appName,$controllerName,$actionName,$request,$response,$session);
    }
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

}