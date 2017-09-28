<?php
namespace App\Home\Controller;

/**
 * 文章控制器
 * Class Word
 * @package App\Home\Controller
 */
class Word extends Base
{
    # 全部文章
    public function index()
    {
        $this -> display();
    }
    # 发布文章
    public function add()
    {
        $this -> display();
    }
    # 文章详情
    public function detail()
    {
        $this -> display();
    }
}