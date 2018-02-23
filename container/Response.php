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
            // TODO 一般应用异常
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
            // TODO 一般应用异常
        }

    }

    /**
     * 404 NotFound
     */
    public function NotFound()
    {
        if(!$this -> is_end){
            $this -> response -> status();
            return$this -> response -> end();
        }else{
            // TODO 一般应用异常
        }
    }
}