<?php
namespace Service;
use Itxiao6\Database\Eloquent\Model as Eloquent;
use Itxiao6\Database\Eloquent\SoftDeletes;
use Kernel\Event;

/**
* 模型父类
*/
class Model extends Eloquent
{
    use SoftDeletes;
    /**
     * 模型的连接名称。
     *
     * @var string
     */
//    protected $connection = 'default';
    /**
    * 应该被调整为日期的属性
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
    /**
     * 这是模型的表定义
     * @var string
     */
    protected $table;

    /**
     * 这里是表主键的定义
     * @var string
     */
    protected $primaryKey = 'id';

    /**
    * 自动自增的主键类型
    *
    * @var string
    */
    protected $keyType = 'int';

    /**
    * id是否自动自增
    *
    * @var bool
    */
    public $incrementing = true;

    /**
    * 是否创建模型
    *
    * @var bool
    */
    public $timestamps = true;


    /**
    * 要隐藏的字段
    *
    * @var array
    */
    protected $hidden = ['deleted_at'];

    /**
    * 要显示的字段
    *
    * @var array
    */
    protected $visible = [];

    /**
     * 实例化一个模型
    */
    public function __construct()
    {
        # 检查数据库链接
        if(!Event::get_databases_status()){
            Event::databases_connection();
        }
        # 调用父类构造方法
        parent::__construct(...func_get_args());
    }
}
