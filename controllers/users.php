<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 17:42
 */
require_once __DIR__.'/users/user_login.php';
require_once __DIR__.'/users/user_logout.php';
session_start();

$tmp=file_get_contents("php://input");
$data=json_decode($tmp,true);


if(isset($_GET['nickname']) && isset($_GET['password'])){
        //用户登陆
    echo user_login($_GET['nickname'], $_GET['password']);
}else if (isset($_GET['logout'])){
    //用户登出
    if($_GET['logout'] === ''){
        echo user_logout();
    }

}
