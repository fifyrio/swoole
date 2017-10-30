<?php
namespace App\Home\Controller;
use App\Model\User;
use Itxiao6\Route\Bridge\Http;

/**
 * 前台首页控制器
 * Class Index
 * @package App\Home\Controller
 */
class Index extends Base{
  # 首页操作
  public function index(){
      # 渲染模板
      var_dump(rand(0,500));
//      Http::output('');
      #渲染模板
        $this -> display();
  }
}