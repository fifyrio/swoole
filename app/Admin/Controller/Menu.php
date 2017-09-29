<?php
namespace App\Admin\Controller;
use Service\Exception;

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
            if($data['list'] = \App\Model\Menu::skip($start) -> take($page_num) -> orderByRaw('concat(path,id),pid DESC') -> get()){
                $data['list'] = $data['list'] -> toArray();
            }
            # 处理数据
            foreach ($data['list'] as $key=>$item){
                if($item['pid'] != 0){
                    $data['list'][$key]['title'] = '|--'.$item['title'];
                }
            }
            # 获取一级导航
            if($data['pnav'] = \App\Model\Menu::where(['pid'=>0]) -> pluck('title','id')){
                $data['pnav'] = $data['pnav'] -> toArray();
            }
            # 设置根导航的名称
            $data['pnav'][0] = '根';
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
            try{
                \App\Model\Menu::add_data($_POST);
                $this -> message('添加成功');
            }catch (\Exception $exception){
                $this -> message($exception -> getMessage());
            }
        }else{
            # 获取一级导航
            $this -> assign('nav_data',\App\Model\Menu::where(['pid'=>0]) -> get());
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
            $this -> message('次导航下面还有子级导航不能直接删除');
        }
        if(\App\Model\Menu::delete($_GET['id'])){
            $this -> message('删除成功');
        }else{
            $this -> message('删除失败');
        }
    }
}