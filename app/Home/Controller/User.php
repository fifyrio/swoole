<?php
namespace App\Home\Controller;

/**
 * 用户操作类
 * Class User
 * @package App\Home\Controller
 */
class User extends Base
{
    # 用户登录
    public function login()
    {
        $this -> display();
    }
    # 我的帖子
    public function index()
    {
        $this -> display();
    }
    # 用户注册
    public function reg()
    {
        $this -> display();
    }
    # 基本设置
    public function set()
    {
        $this -> display();
    }
    # 我的消息
    public function message()
    {
        $this -> display();
    }
}