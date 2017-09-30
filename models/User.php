<?php
namespace App\Model;
use Kernel\Model;
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
}