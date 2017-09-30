<?php
namespace App\Model;
use Kernel\Model;

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
        dd(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$data['email']));
        # 邮箱
        if(isset($data['email']) && $data['email']!='' && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$data['email'])){
            $insert['email'] = $data['email'];
        }else{
            throw new \Exception('无效的邮箱');
        }
        # 邮箱
        if(isset($data['nickname']) && $data['nickname']!=''){
            $insert['nickname'] = $data['nickname'];
        }else{
            throw new \Exception('无效的图标');
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