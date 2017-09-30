<?php
namespace App\Home\Controller;
use Service\Verify;

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
        if(IS_POST){
            # 验证码验证
            $verify = new Verify;
            if(!$verify -> check($_POST['vcode'],'reg')){
                $this -> ajaxReturn(['status'=>2,'msg'=>'验证码错误']);
            }
            # 添加用户数据
            try{
                \App\Model\User::add_user($_POST);
                $this -> ajaxReturn(['status'=>1,'msg'=>'注册成功']);
            }catch (\Exception $exception){
                $this -> ajaxReturn(['status'=>2,'msg'=>$exception -> getMessage()]);
            }
        }else{
            $this -> display();
        }
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
    # 激活邮箱
    public function activate()
    {
        $this -> display();
    }
    # 找回密码/重置密码
    public function forget()
    {
        $this -> display();
    }
    # 用户主页
    public function home()
    {
        $this -> display();
    }
}