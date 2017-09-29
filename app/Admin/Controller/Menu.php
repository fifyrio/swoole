<?php
namespace App\Admin\Controller;
/**
 * 导航管理
 * Class Menu
 * @package App\Admin\Controller
 */
class Menu extends Base
{
    # 导航列表
    public function index()
    {
        if(isset($_GET['data'])){
            # 每页数量
            $page_num = $_GET['pageSize'];
            $data = [
                'rel'=>true,
                'msg'=>'获取成功',
            ];
            # 当前页
            $page = (isset($_GET['pageIndex']) && $_GET['pageIndex'])?$_GET['pageIndex']:1;
            # 从哪开始
            $start = $page_num * ($page-1);
            # 获取数据
            if($data['list'] = \App\Model\Menu::skip($start) -> take($page_num) -> orderBy('sort','DESC') -> orderBy('id','DESC') -> get()){
                $data['list'] = $data['list'] -> toArray();
            }
            # 获取总数量
            $data['count'] = \App\Model\Menu::count();
            # 返回数据
            $this -> ajaxReturn($data);
        }else{
            # 渲染模板
            $this -> display();
        }
    }
    # 添加导航
    public function add()
    {
        if(IS_POST){
            dd($_POST);
        }else{
            # 获取一级导航
            $this -> assign('nav_data',Menu::where(['pid'=>0]) -> get());
            # 分配图标数据
            $this -> assign('icon_data',$this -> icon_data);
            # 渲染模板
            $this -> display();
        }
    }
    # 修改导航
    public function edit()
    {
        if(IS_POST){
            dd($_POST);
        }else{
            $this -> display();
        }
    }
    # 删除导航
    public function delete()
    {
        # 判断是否存在下级
        if(\App\Model\Menu::where(['pid'=>$_GET['id']]) -> first()){
            $this -> error('次导航下面还有子级导航不能直接删除');
        }
        if(\App\Model\Menu::delete($_GET['id'])){
            $this -> success('删除成功');
        }else{
            $this -> error('删除失败');
        }
    }
}