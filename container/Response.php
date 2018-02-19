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
}