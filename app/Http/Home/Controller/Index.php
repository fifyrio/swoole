<?php
namespace App\Http\Home\Controller;
use Container\Request;
use Container\Response;

/**
 * 前台首页控制器
 * Class Index
 * @package App\Home\Controller
 */
class Index extends Base{
    # 首页操作
    public function index(Request $request,Response $response,$session){
//        $session -> set('name','itxiao6');
//        $response -> write('hello:'.$session -> get('name'));
//        $response -> write('hello');
//        return $response -> end();
        $this -> assign('name',$session -> get('name'));
        $this -> display();
    }
}