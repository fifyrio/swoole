<?php
namespace App\Model;
use Kernel\Model;
use Service\Exception;

/**
 * 配置模型
 * Class Menu
 * @package App\Model
 */
class Menu extends Model
{
    //--
    //-- 表的结构 `menu`
    //--
    //
    //CREATE TABLE `menu` (
    //`id` int(11) NOT NULL,
    //`name` varchar(50) NOT NULL COMMENT '导航名称',
    //`controller` varchar(50) DEFAULT NULL COMMENT '控制器名称',
    //`action` varchar(50) DEFAULT NULL COMMENT '操作名称',
    //`icon` varchar(50) NOT NULL DEFAULT 'fa-list-alt' COMMENT '图标',
    //`pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
    //`created_at` varchar(50) NOT NULL COMMENT '数据创建时间',
    //`updated_at` varchar(50) NOT NULL COMMENT '数据最后更新时间',
    //`deleted_at` varchar(50) DEFAULT NULL COMMENT '删除时间'
    //) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台导航表';
    # 数据
    //INSERT INTO `menu` (`id`, `name`, `controller`, `action`, `icon`, `pid`, `href`, `created_at`, `updated_at`, `deleted_at`) VALUES
    //(1, '微信管理', 'Wechat', NULL, 'fa-list-alt', 0, '', '1504598389', '1504598389', NULL),
    //(2, '微信菜单管理', 'Wechat', 'menu', 'fa-list-alt', 1, '/Wechat/menu.html', '1504598389', '1504598389', NULL),
    //(3, '微信参数配置', 'Wechat', 'config', 'fa-list-alt', 1, '/Wechat/config.html', '1504598389', '1504598389', NULL),
    //(4, '权限管理', 'Jurisdiction', NULL, 'fa-list-alt', 0, NULL, '1504598389', '1504598389', NULL),
    //(5, '角色管理', 'Jurisdiction', 'type', 'fa-list-alt', 4, '/Jurisdiction/type.html', '1504598389', '1504598389', NULL),
    //(6, '结点管理', 'Jurisdiction', 'node', 'fa-list-alt', 4, '/Jurisdiction/node.html', '1504598389', '1504598389', NULL),
    //(7, '用户管理', 'Jurisdiction', 'user', 'fa-list-alt', 4, '/Jurisdiction/user.html', '1504598389', '1504598389', NULL);

    /**
     * 表名
     * @var string
     */
    protected $table = 'menu';

    /**
     * 关联子级导航
     * @return \Itxiao6\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this -> hasMany(self::class,'pid','id')
//            -> remember(3600*24)
            ;
    }

    /**
     * 添加导航
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function add_data($data = [])
    {
        # 定义要插入的数据
        $insert = [];
        # 判断数组是否为空
        if(count($data)==0){
            throw new \Exception('数据不能为空');
        }
        # 类型过滤
        $data['sort'] = (Int) $data['sort'];
        # 判断排序位是否有效
        if(isset($data['sort']) && $data['sort']!=''){
            $insert['sort'] = $data + 0;
        }else{
            throw new \Exception('排序位无效');
        }
        # 判断导航名称是否有效
        if(isset($data['title']) && $data['title']!=''){
            $insert['title'] = $data['title'];
        }else{
            throw new \Exception('无效的导航名称');
        }
        # 控制器名称
        if(isset($data['controller']) && $data['controller']!=''){
            $insert['controller'] = $data['controller'];
        }else{
            throw new \Exception('无效的控制器名称');
        }
        # 图标
        if(isset($data['icon']) && $data['icon']!=''){
            $insert['icon'] = $data['icon'];
        }else{
            throw new \Exception('无效的图标');
        }
        # 所属导航是否有效
        if(isset($data['pid']) && $data['pid']!='' && self::where(['id'=>$data['pid']]) -> first()){
            $insert['pid'] = $data['pid'];
        }else{
            throw new \Exception('所属导航无效');
        }
        # 导航链接
        if(isset($data['href']) && $data['href']!=''){
            $insert['href'] = $data['href'];
        }else if($data['pid']==0){
            if(isset($data['href'])){
                unset($data['href']);
            }
        }else if($data['pid']!=0){
            $insert['href'] = '/'.$data['controller'].'/'.$data['action'].'.html';
        }
        # 数据创建时间
        $insert['created_at'] = time();
        # 数据最后更新时间
        $insert['updated_at'] = $insert['created_at'];
        if(self::insert($insert)){
            return true;
        }else{
            throw new \Exception('添加导航失败');
        }
    }

}