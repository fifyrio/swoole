<?php
namespace Service;
use Kernel\Config;
/**
 * url 处理类
 * Class Url
 * @package Service
 */
class Url
{
    /**
     * 生成url
     * @param $mode 模型
     * @param array $param 参数
     * @return string
     */
    public static function make($mode,$param = [])
    {
        # 拆分模块
        $data = explode('.',$mode);
        # 人性化兼容
        if(count($data)==3){
            # 组合url
            $url = '/'.implode(Config::get('sys','url_split'),$data).'.html';
        }else if(count($data)==2){
            $url = '/'.
                Config::get('host',$_SERVER['HTTP_HOST'])?APP_NAME.
                Config::get('sys','url_split')
                :''.
                implode(Config::get('sys','url_split'),$data).'.html';
        }else if(count($data)==1){
            $url = '/'.
                Config::get('host',$_SERVER['HTTP_HOST'])?APP_NAME.
                Config::get('sys','url_split')
                :''.
                CONTROLLER_NAME.
                Config::get('sys','url_split').
                implode(Config::get('sys','url_split'),$data).'.html';
        }
        # 判断是否存在参数
        if(is_array($param) && count($param) > 1 ){
            $url .= '?';
            foreach ($param as $key=>$item){
                $url .= $key.'='.$item.'&';
            }
        }

        # 返回结果
        return $url;
    }
}