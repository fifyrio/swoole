<?php
namespace Kernel;
use Illuminate\Container\Container;
use Service\DB;
use Itxiao6\Database\Eloquent\Model as Eloquent;
use Itxiao6\Database\Eloquent\SoftDeletes;
use Kernel\Config;
/**
* 模型父类
*/
class Model extends Eloquent
{
    use SoftDeletes;

    /**
    * 应该被调整为日期的属性
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
    /**
    * 这是模型的表定义
    *
    * @var string
    */
    protected $table;

    /**
    * 这里是表主键的定义
    *
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
    * The number of models to return for pagination.
    *
    * @var int
    */
    protected $perPage = 15;

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
    * 日期字段的储存格式
    *
    * @var string
    */
    protected $dateFormat;

    /**
    * 获取当前时间
    *
    * @return int
    */
    public function freshTimestamp()
    {
        return time();
    }

    /**
    * 避免转换时间戳为时间字符串
    *
    * @param DateTime|int $value
    * @return DateTime|int
    */
    public function fromDateTime($value)
    {
        return $value;
    }

    /**
    * select的时候避免转换时间为Carbon
    *
    * @param mixed $value
    * @return mixed
    */
    #  protected function asDateTime($value)
    # {
    #     return $value;
    #  }


    /**
    * 从数据库获取的为获取时间戳格式
    *
    * @return string
    */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * 实例化一个模型
    */
    public function __construct()
    {
        # 检查数据库链接
        DB::connection_databases();
        # 调用父类构造方法
        parent::__construct(func_get_args());
    }
}
