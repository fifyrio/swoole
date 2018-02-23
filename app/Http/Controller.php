<?php
namespace App\Http;
use Kernel\Config;
use Itxiao6\View\Compilers\ViewCompiler;
use Itxiao6\View\Engines\CompilerEngine;
use Itxiao6\View\FileViewFinder;
use Itxiao6\View\Factory;

/**
 * 控制器基类
 * Class Controller
 * @package App\Http
 */
class Controller
{
    /**
     * 请求
     * @var null|mixed
     */
    protected $request = null;
    /**
     * 响应
     * @var null|mixed
     */
    protected $response = null;
    /**
     * session 会话
     * @var null |mixed
     */
    protected $session = null;
    /**
     * 路由匹配到的信息
     * @var array
     */
    protected $mvc = [
        'app'=>'Home',
        'controller'=>'Index',
        'action'=>'index',
        'method'=>'GET',
    ];
    /**
     * 模板数据
     * @var array
     */
    protected $viewData = [];

    /**
     * 获取模板内容
     * @param null $view
     * @param array $data
     * @param null $view_path
     * @return mixed
     */
    public function display($view = null,$data = [],$view_path = null)
    {
        # 定义模板目录
        if($view_path === null){
            $view_path = Config::get('sys','view_path');
        }
        # 当前模块的模板路径
        $view_path[] = ROOT_PATH.'app'.DS.'Http'.DS.$this -> mvc['app'].DS.'View'.DS.$this -> mvc['controller'];
        # 当前应用的模板路径
        $view_path[] = ROOT_PATH.'app'.DS.'Http'.DS.$this -> mvc['app'].DS.'View';
        # 循环处理模板目录
        foreach($view_path as $value){
            # 判断模板目录是否存在
            if(!is_dir($value) ){
//            TODO 抛出普通异常 模板编译目录不存在或没有权限
            }
        }
        # 判断模板是否存在
        if(!file_exists(ROOT_PATH.'runtime'.DS.'view')){
            mkdir(ROOT_PATH.'runtime'.DS.'view');
        }
        # 判断模板编译目录是否存在并且有写入的权限
        if(!is_writable(ROOT_PATH.'runtime'.DS.'view')){
//            TODO 抛出普通异常 模板编译目录没有权限
        }
        # 实例化View 的编译器
        $compiler = new ViewCompiler(ROOT_PATH.'runtime'.DS.'view');
        # 实例化 编译器
        $engine = new CompilerEngine($compiler);
        # 实例化文件系统
        $finder = new FileViewFinder($view_path,Config::get('sys','extensions'));
        # 实例化模板
        $factory = new Factory($engine,$finder);
        # 判断是否传入的模板名称
        if($view === null){
            $view = $this -> mvc['controller'].'.'.$this -> mvc['action'];
        }else{
            $view = str_replace('/','.',$view);
        }
        # 判断是否传了要分配的值
        if(count($data) > 0){
            # 分配模板的数组合并
            $this -> assign($data);
        }
        # 判断模板是否存在
        if(!$factory -> exists($view)){
//            TODO 抛出普通异常 模板找不到
        }
        # 解析模板
        $factory -> make($view,$this -> viewData);
        # 发送渲染结果
        return $this -> response -> write($factory -> toString());
    }

    /**
     * 分配变量值
     * @param $key
     * @param array $data
     * @return $this
     */
    protected function assign($key,$data = []){
        # 判断传的是否为数组
        if( is_array($key) ){
            $this -> viewData = array_merge($this -> viewData, $key);
        }else{
            $this -> viewData = array_merge($this -> viewData,[$key => $data]);
        }
        # 返回本对象
        return $this;
    }

    /**
     * 获取控制器接口
     * @param $appName
     * @param $controllerName
     * @param $actionName
     * @param $request
     * @param $response
     * @param $session
     */
    public static function getInterface($appName,$controllerName,$actionName,$request,$response,$session)
    {
        new static($appName,$controllerName,$actionName,$request,$response,$session);
    }

    /**
     * 控制器构造方法
     * @param $appName
     * @param $controllerName
     * @param $actionName
     * @param $request
     * @param $response
     * @param $session
     */
    protected function __construct($appName,$controllerName,$actionName,$request,$response,$session)
    {
        /**
         * 判断操作是否存在
         */
        if(!method_exists($this,$actionName)){
            return $response -> NotFound();
        }
        /**
         * 判断是否定义了初始化方法
         */
        if(method_exists($this,'__init')){
            $this -> __init();
        }
        /**
         * 设置请求
         */
        $this -> request = $request;
        /**
         * 设置响应
         */
        $this -> response = $response;
        /**
         * 设置会话信息
         */
        $this -> session = $response;
        /**
         * 操作名称过滤
         */
        if(in_array($actionName,['__construct','getInterface'])){
            return $response -> NotFound();
        }
        /**
         * 启动操作
         */
        $this -> $actionName($request,$response,$session);
        /**
         * app name
         */
        $this -> mvc['app'] = $appName;
        /**
         * controller name
         */
        $this -> mvc['controller'] = $controllerName;
        /**
         * action name
         */
        $this -> mvc['action'] = $actionName;
    }
}