<?php
namespace App\Http;
use Container\Request;
use Container\Response;
use Itxiao6\Route\Route;
use Itxiao6\Session\Session;
use Kernel\Config;
use Kernel\Event;
use Service\Exception;
use Service\Whoops;

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
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws \Exception
     */
    public static function start(Request $request = null,Response $response = null)
    {
        /**
         * Whoops
         */
//        $whoops = new \Whoops\Run;
//        $whoops->pushHandler(new Whoops($request,$response));
//        $whoops->register();
        /**
         * SESSION
         */
        $session = Event::session($request,$response);
        /**
         * Route ing
         */
        Route::getInterface($request,$response) ->
            config('keyword',Config::get('sys','url_split')) ->
            start(function($app,$controller,$action) use($request,$response,$session){
                Event::web_route($app,$controller,$action,$request,$response,$session);
        });
    }
}