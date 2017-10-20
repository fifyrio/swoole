<?php
namespace Service;
use Kernel\Config;
use Service\DB;
/**
 * 计时器
 * Class Timeer
 * @package Service
 */
class Timeer{
    /**
     * 开始时间
     * @var
     */
    protected static $start_time;
    /**
     * 结束时间
     * @var
     */
    protected static $end_time;
    /**
     * 事件记录
     * @var array
     */
    protected static $event_time = [];

    /**
     * 开始计时
     * @return Timeer
     */
    public static function start(){
        self::$start_time = explode(' ',microtime());
    }

    /**
     * 计算用时
     * @return float
     */
    public static function end($name = ''){
        self::$end_time = explode(' ',microtime());
        $thistime = self::$end_time[0]+self::$end_time[1]-(self::$start_time[0]+self::$start_time[1]);
        $thistime = round($thistime,3);
        # 判断是否开启debugbar
        if(Config::get('sys','debugbar')){
            # 获取全局变量
            global $debugbar;
            global $debugbarRenderer;
            # 添加事件信息到debugbar
            $debugbar["Time"] -> addMessage($name.'用时:'.$thistime.'秒');
        }
        self::$start_time = explode(' ',microtime());
    }
}