<?php
namespace App\Home\Controller;
use App\Model\User;
/**
 * 前台首页控制器
 * Class Index
 * @package App\Home\Controller
 */
class Index extends Base{
  # 首页操作
  public function index(){
  	include('test.php');
  	User::get();
      # 渲染模板
      return $this -> display();
  }
}