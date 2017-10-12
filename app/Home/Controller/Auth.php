<?php
namespace App\Home\Controller;
use Kernel\Controller;
use Service\Url;
use Service\Verify;
use App\Model\User;

/**
 * 鉴权控制器
 * Class Auth
 * @package App\Home\Controller
 */
class Auth extends Controller
{
    /**
     * 获取验证码
     */
    public function get_code()
    {
        $verify = new Verify();
        $verify -> entry($_GET['id']);
    }
    # 用户登录
    public function login()
    {
        # 判断是否已经登陆过了
        if(isset($_SESSION['home']['user']['id']) && $_SESSION['home']['user']['id']!=''){
            $this -> redirect('Index.index');
        }
        if(IS_POST){
            try{
                # 验证码验证
                $verify = new Verify;
                if(!$verify -> check($_POST['vcode'],'login')){
                    $this -> ajaxReturn(['status'=>1,'msg'=>'验证码错误']);
                }
                # 登录
                User::login($_POST['email'],$_POST['password'],function($user){
                    $_SESSION['home']['user'] = $user -> toArray();
                });
                $this -> ajaxReturn(['msg'=>'登录成功','status'=>0,'url'=>Url::make('Index.index')]);
            }catch (\Exception $exception){
                $this -> ajaxReturn(['status'=>1,'msg'=>$exception -> getMessage()]);
            }
        }else{
            return $this -> display();
        }
    }
    # 用户注册
    public function reg()
    {
        if(IS_POST){
            # 验证码验证
            $verify = new Verify;
            if(!$verify -> check($_POST['vcode'],'reg')){
                $this -> ajaxReturn(['status'=>1,'msg'=>'验证码错误']);
            }
            # 添加用户数据
            try{
                User::add_user($_POST);
                $this -> ajaxReturn(['status'=>0,'msg'=>'注册成功']);
            }catch (\Exception $exception){
                $this -> ajaxReturn(['status'=>1,'msg'=>$exception -> getMessage()]);
            }
        }else{
            return $this -> display();
        }
    }
    # 退出登录
    public function logout()
    {
        $_SESSION['home'] = null;
        $this -> redirect('Auth.login');
    }
}