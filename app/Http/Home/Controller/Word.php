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
        return $this -> display();
    }
    # 发布文章
    public function add()
    {
        return $this -> display();
    }
    # 文章详情
    public function detail()
    {
        return $this -> display();
    }
    # 编辑文章
    public function edit()
    {
        return $this -> display();
    }
}