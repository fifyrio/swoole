<?php
namespace Container;
/**
 * 响应类
 * Class Response
 * @package Container
 */
class Response
{
    /**
     * 请求
     * @var null
     */
    protected $response = null;
    /**
     * 此请求是否已经结束
     * @var bool
     */
    protected $is_end = false;
    /**
     * 获取接口
     * @param $response
     * @return static
     */
    public static function getInterface($response)
    {
        return new static($response);
    }

    /**
     * 构造方法
     * Response constructor.
     * @param $response
     */
    protected function __construct($response)
    {
        $this -> response = $response;
    }
    /**
     * 获取或设置响应
     * @param null $response
     * @return $this
     */
    public function RawResponse($response = null)
    {
        if($response === null){
            return $this -> response;
        }else{
            $this -> response = $response;
        }
        return $this;
    }

    /**
     * 向客户端发送信息
     */
    public function write()
    {
        if(!$this -> is_end){
            return $this -> response -> write(...func_get_args());
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 结束当前请求
     */
    public function end()
    {
        if(!$this -> is_end){
            $this -> response -> end();
            $this -> is_end = true;
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 设置cookie
     * @param $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed
     */
    public function set_cookie($key,$value = '',$expire = 0,$path = '/',$domain  = '',$secure = false , $httponly = false)
    {
        if(!$this -> is_end){
            return $this -> response -> cookie($key,$value,$expire,$path,$domain,$secure,$httponly);
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 设置header 头
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set_header($key,$value)
    {
        if(!$this -> is_end){
            return $this -> response -> header($key,$value);
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 响应状态码
     * @param $http_status_code
     * @return mixed
     */
    public function set_status($http_status_code)
    {
        if(!$this -> is_end){
            return $this -> response -> status($http_status_code);
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 启用Http GZIP压缩
     * @param int $level 1-9
     * @return mixed
     */
    public function gzip($level = 1)
    {
        if(!$this -> is_end){
            return $this -> response -> gzip($level);
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 发送文件到客户端
     * @param $filename
     * @param int $offset
     * @param int $length
     * @return mixed
     */
    public function sendfile($filename,$offset = 0,$length = 0)
    {
        if(!$this -> is_end){
            return $this -> response -> sendfile($filename,$offset,$length);
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }

    /**
     * 404 NotFound
     */
    public function NotFound()
    {
        if(!$this -> is_end){
            $this -> set_status(404);
            return $this -> end();
        }else{
            // TODO 一般应用异常 请求已经结束
        }
    }
}