<?php
namespace App\Admin\Controller;
use App\Model\Menu;
use Service\Upload;
use Config;

/**
 * 后台首页控制器
 * Class Index
 * @package App\Admin\Controller
 */
class Index extends Base{
    # 后台首页
    public function index()
    {
        #渲染模板
        $this -> display();
    }
    # 欢迎页
    public function main()
    {
        $this -> display();
    }
    public function table()
    {
        $this -> display();
    }
}
