<?php
namespace App\Http\Home\Controller;
use Container\Request;
use Container\Response;
use Itxiao6\Session\Interfaces\Storage;

/**
 * 前台首页控制器
 * Class Index
 * @package App\Home\Controller
 */
class Index extends Base{
    # 首页操作
    public function index(Request $request,Response $response,Storage $session){
//        $session -> set('name','itxiao6');
//        $response -> write('hello:'.$session -> get('name'));
//        $response -> end();
//        $response -> write('hello');
//        return $response -> end();
//        $this -> assign('user',\App\Model\User::take(15) -> get());
//        $this -> response -> exception(new \Exception('测试'));
        $this -> display();
    }
}