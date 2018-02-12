<?php
namespace App\Model;
use Service\Model;
use Service\Hash;

/**
 * 用户模型
 * Class User
 * @package App\Model
 */
class User extends Model
{
    /**
     * 表名
     * @var string
     */
    protected $table = 'users';

    /**
     * 用户登录
     * @param array $data
     * @throws \Exception
     */
    public static function add_user($data = [])
    {
        # 定义要插入的数据
        $insert = [];
        # 判断数组是否为空
        if(count($data)==0){
            throw new \Exception('数据不能为空');
        }
        # 邮箱
        if(isset($data['email']) &&
            $data['email']!='' &&
            preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$data['email']) != 0){
            $insert['email'] = $data['email'];
        }else{
            throw new \Exception('无效的邮箱');
        }
        # 用户名
        if(isset($data['nickname']) && $data['nickname']!=''){
            $insert['nickname'] = $data['nickname'];
        }else{
            throw new \Exception('无效的昵称');
        }
        # 两次密码验证
        if($data['password'] != $data['repassword']){
            throw new \Exception('两次输入的密码不一样');
        }
        # 密码
        if(isset($data['password']) && $data['password']!=''){
            $insert['password'] = Hash::make($data['password']);
        }else{
            throw new \Exception('无效的密码');
        }
        # 数据创建时间
        $insert['created_at'] = time();
        # 数据最后更新时间
        $insert['updated_at'] = $insert['created_at'];
        try{
            self::insert($insert);
        }catch (\Itxiao6\Database\QueryException $exception){
            throw new \Exception($exception -> getMessage());
        }
    }

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @param \Closure $fun
     * @return mixed
     * @throws \Exception
     */
    public static function login($username,$password,$fun)
    {
        # 获取要登录的密码
        $user_password = self::where(['email'=>$username]) -> value('password');
        # 验证
        if(Hash::check($password,$user_password)){
            # 回调
            $fun(self::where(['email'=>$username]) -> first());
        }else{
            throw new \Exception('登录失败');
        }
    }

    /**
     * @param array $data
     * @param \Closure $fun
     * @throws \Exception
     */
    public static function set_info($data = [],$fun){
        switch ($data['action']){
            case 'info':
                # 判断邮箱是否有效
                $preg_str = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
                if(isset($data['email']) &&
                    $data['email']!='' &&
                    preg_match($preg_str,$data['email']) != 0 &&
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> value('email')!=$data['email']
                ){
                    # 设置email
                    $info['email'] = $data['email'];
                }
                # 判断昵称是否有效
                if(isset($data['nickname']) &&
                    $data['nickname']!='' &&
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> value('nickname') != $data['nickname']
                ){
                    $info['nickname'] = $data['nickname'];
                }
                # 判断城市是否有效
                if(isset($data['city']) &&
                    $data['city']!='' &&
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> value('city') != $data['city']
                ){
                    $info['city'] = $data['city'];
                }
                # 判断签名是否有效
                if(isset($data['sign']) &&
                    $data['sign']!='' &&
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> value('sign_name') != $data['sign']
                ){
                    $info['sign_name'] = $data['sign'];
                }
                # 判断是否存在要修改的内容
                if(count($info) == 0){
                    throw new \Exception('没有要修改的内容');
                }
                try{
                    # 修改
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> update($info);
                    # 回调
                    $fun(self::where(['id'=>$_SESSION['home']['user']['id']]) -> first());
                }catch (\Itxiao6\Database\QueryException $exception){
                    throw new \Exception($exception -> getMessage());
                }
                # 设置用户信息
                break;
            case 'headimg':
                # 判断头像是否有效
                if(isset($data['avatar']) && $data['avatar']!=''){
                    $info['avatar'] = $data['avatar'];
                }
                # 判断头像是否需要修改
                if(count($info) == 0){
                    throw new \Exception('没有要修改的内容');
                }
                try{
                    # 设置用户头像
                    self::where(['id'=>$_SESSION['home']['user']['id']]) -> update($info);
                    # 回调
                    $fun(self::where(['id'=>$_SESSION['home']['user']['id']]) -> first());
                }catch (\Exception $exception){
                    throw new \Exception($exception -> getMessage());
                }
                break;
            case 'pass':
                # 判断两次输入的密码是否相同
                if(!(isset($data['pass']) && isset($data['repass']) && $data['pass'] == $data['repass'])){
                    throw new \Exception('两次输入的密码不同');
                }
                # 获取现在的密码
                try{
                    $now_hash_pass = self::where(['id'=>$_SESSION['home']['user']['id']]) -> value('password');
                }catch (\Itxiao6\Database\QueryException $exception){
                    throw new \Exception($exception -> getMessage());
                }
                # 验证当前密码是否正确
                if(Hash::check($data['nowpass'],$now_hash_pass)){
                    try{
                        # 设置新密码
                        self::where(['id'=>$_SESSION['home']['user']['id']]) ->
                        update(['password'=>Hash::make($data['pass'])]);
                        # 回调
                        $fun(self::where(['id'=>$_SESSION['home']['user']['id']]) -> first());
                    }catch (\Exception $exception){
                        throw new \Exception($exception -> getMessage());
                    }catch (\Itxiao6\Database\QueryException $exception){
                        throw new \Exception($exception -> getMessage());
                    }
                }else{
                    throw new \Exception('旧密码不正确');
                }
                break;
            case 'bind':
                # 账号绑定
                break;
            default:
                throw new \Exception('找不到指定操作');
        }
    }
}