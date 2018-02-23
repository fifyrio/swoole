<?php
namespace App\Http;
use Container\Request;
use Container\Response;
use Itxiao6\Route\Route;
use Itxiao6\Session\Session;
use Kernel\Config;
use Service\Exception;
/**
* 框架核心类
*/
class Kernel
{
    /**
     * 加载环境变量
     */
    public static function load_env()
    {
        # 判断环境变量配置文件是否存在
        if(file_exists(ROOT_PATH.'.env')){
            # 自定义配置
            $f= fopen(ROOT_PATH.'.env',"r");
        }else{
            # 惯例配置
            $f= fopen(ROOT_PATH.'.env.example',"r");
        }
        # 循环行
        while (!feof($f))
        {
            $line = fgets($f);
            # 替换单个空格
            $line = preg_replace('! !','',$line);
            # 替换连续空格
            $line = preg_replace('! +!','',$line);
            # 替换制表符或空格
            $line = preg_replace('!\s+!','',$line);
            if((!strstr($line,'#')) && $line!=''){
                # 设置环境变量
                putenv(preg_replace('!\n$!','',$line));
            }
        }
        # 关闭文件
        fclose($f);
    }

    /**
     * 启动框架
     * @param null $request
     * @param null $response
     * @return mixed
     * @throws \Exception
     */
    public static function start($request = null,$response = null)
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
//        if(!is_dir(ROOT_PATH.'runtime'.DS.'session'.DS)){
//            mkdir(ROOT_PATH.'runtime'.DS.'session'.DS);
//        }
//        $session = Session::getInterface($request,$response) -> start(ROOT_PATH.'runtime'.DS.'session'.DS);
        /**
         * MySql Session
         */
//        $pdo = new \PDO("mysql:host=47.104.85.153;dbname=new_baihua",'new_baihua','new_baihua2017');
//        $session = Session::getInterface($request,$response) -> driver('MySql') -> start($pdo,'session_table');
        /**
         * Route ing
         */
        Route::getInterface($request,$response) ->
            config('keyword',Config::get('sys','url_split')) ->
            start(function($app,$controller,$action) use($request,$response){
                $response -> RawResponse() -> write('app:'.$app.',controller:'.$controller.',action:'.$action);
                $response -> RawResponse() -> end();
            });
    }
}