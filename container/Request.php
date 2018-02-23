<?php
namespace Container;

/**
 * 请求类
 * Class Request
 * @package Itxiao6\Interfaces\Tools
 */
class Request
{
    /**
     * 请求
     * @var null
     */
    protected $request = null;
    /**
     * 获取接口
     * @param $request
     * @return static
     */
    public static function getInterface($request)
    {
        return new static($request);
    }
    /**
     * 构造方法
     * Request constructor.
     * @param $request
     */
    protected function __construct($request)
    {
        $this -> request = $request;
    }
    /**
     * 获取或设置请求
     * @param $request
     * @return mixed
     */
    public function RawRequest($request = null)
    {
        if($request === null){
            return $this -> request;
        }else{
            $this -> request = $request;
        }
        return $this;
    }
    /**
     * 获取header 信息
     * @param null|string $name
     * @return null|array
     */
    public function header($name = null)
    {
        if($name != null){
            return isset($this -> request -> header[$name])?$this -> request -> header[$name]:null;
        }else{
            return $this -> request -> header;
        }
    }
    /**
     * 获取server 信息
     * @param null|string $name
     * @return null|array
     */
    public function server($name = null)
    {
        if($name != null){
            return isset($this -> request -> server[$name])?$this -> request -> server[$name]:null;
        }else{
            return $this -> request -> server;
        }
    }
    /**
     * 获取上传的 files
     * @param null|string $name
     * @return null|array
     */
    public function files($name = null)
    {
        if($name != null){
            return isset($this -> request -> files[$name])?$this -> request -> files:null;
        }else{
            return $this -> request -> files;
        }
    }
    /**
     * 获取原生的请求内容
     */
    public function rawContent()
    {
        return $this -> request -> rawContent();
    }
    /**
     * 获取cookie 信息
     * @param null|string $name
     * @return null|array
     */
    public function cookie($name = null)
    {
        if($name != null){
            return isset($this -> request -> cookie[$name])?$this -> request -> cookie[$name]:null;
        }else{
            return $this -> request -> cookie;
        }
    }
    /**
     * 获取post 请求参数
     * @param null|string $name
     * @return mixed
     */
    public function post($name = null)
    {
        if($name != null){
            isset($this -> request -> post[$name])?$this -> request -> post[$name]:null;
        }else{
            return $this -> request -> post;
        }
    }
    /**
     * 获取get 请求参数
     * @param null|string $name
     * @return null
     */
    public function get($name = null)
    {
        if($name != null){
            return isset($this -> request -> get[$name])?$this -> request -> get[$name]:null;
        }else{
            return $this -> request -> get;
        }
    }
}