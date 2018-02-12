<?php
namespace App\Home\Controller;
use App\Model\Menu;
/**
 * 前台首页控制器
 * Class Index
 * @package App\Home\Controller
 */
class Index extends Base{
    # 首页操作
    public function index(){
        # 打印导航表里的数据
//        $this -> output(function(){
//          var_dump(Menu::get() -> toArray());
//        });
//        return ;
        #渲染模板
        $this -> display();
    }
}