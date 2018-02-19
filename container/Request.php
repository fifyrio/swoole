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
}