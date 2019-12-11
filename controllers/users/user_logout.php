<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 19:04
 */
require_once __DIR__.'/../../tools/mysql_connection.php';
header('Content-Type:application/json');


function user_logout(){
    if(isset($_SESSION["userID"])){
    unset($_SESSION['userID']);
    return json_encode(array("result"=>true,"message"=>"登出成功"),JSON_UNESCAPED_UNICODE);
    }else{
        return json_encode(array("result"=>false,"message"=>"未登录"),JSON_UNESCAPED_UNICODE);
    }
}