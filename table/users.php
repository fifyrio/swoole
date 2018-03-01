<?php
include_once(__DIR__.'/../init.php');
/**
 * 数据库迁移
 */
use Service\DB;
use Itxiao6\Database\Schema\Blueprint;

/**
 * 删除表users
 */
DB::schema() -> dropIfExists('users');

/**
 * 创建 users 表
 */
DB::schema() -> create('users', function(Blueprint $table)
{
    $table->increments('id');
    $table->string('username', 40);
    $table->string('email')->unique();
    $table->timestamps();
});
/**
 * 插入测试数据
 */
DB::table('users')->insert(array(
    array('username' => 'Hello',  'email' => 'hello@world.com'),
    array('username' => 'Carlos',  'email' => 'anzhengchao@gmail.com'),
    array('username' => 'Overtrue',  'email' => 'i@overtrue.me'),
));