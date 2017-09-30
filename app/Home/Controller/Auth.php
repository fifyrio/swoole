<?php
namespace App\Home\Controller;
use Kernel\Controller;
use Service\Verify;

/**
 * 鉴权控制器
 * Class Auth
 * @package App\Home\Controller
 */
class Auth extends Controller
{
    public function get_code()
    {
        $verify = new Verify();
        $verify -> entry($_GET['id']);
    }
}