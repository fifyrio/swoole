<?php
namespace App\Http;
use Container\Request;
use Container\Response;
use Itxiao6\Session\Session;
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
        # 实例化请求
        $request = Request::getInterface($request);
        # 实例化响应
        $response = Response::getInterface($response);
        /**
         * REDIS SESSION
         */
        $redis = new \Redis();
        $redis -> connect('127.0.0.1',6379);
        # 启动会话
        $session = Session::getInterface($request,$response) -> driver('Redis') -> start($redis);
        /**
         * Local File SESSION
         */
//        $session = Session::getInterface($request,$response) -> start(ROOT_PATH.'runtime'.DS.'session'.DS);

         $session -> set('name','戒尺');

        $response -> RawResponse() -> write('你好:'.$session -> get('name'));
        return $response -> RawResponse() -> end();
//
//        $response -> write('111111111');
//        return $response -> end();
        # 设置url 分隔符
//        Route::set_key_word(Config::get('sys','url_split'));
        /**
         * 匹配路由
         */
        try{
            # 加载路由
//            Route::init($request,$response,function($app,$controller,$action){
//                // 后置操作
//            });
            throw new Exception('SUCCESS');
        }catch (\Exception $exception){
            $response -> write('404');
            return $response -> end();
        }
    }
}