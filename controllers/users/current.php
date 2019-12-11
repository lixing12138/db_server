<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 19:00
 */

header('Content-Type:application/json');

session_start();

if(isset($_SESSION['userID'])){

    if (stripos($_SESSION['userID'], "r") === 0) $type = "admin";
    if (stripos($_SESSION['userID'], "s") === 0) $type = "student";
    if (stripos($_SESSION['userID'], "t") === 0) $type = "teacher";

    echo json_encode(array('result' => true, 'message' => "用户存在", 'data'=>['username'=>$_SESSION['userID'], 'type'=>$type]), JSON_UNESCAPED_UNICODE);
}else{
    echo json_encode(array('result'=> false, 'message' => '获取失败'),JSON_UNESCAPED_UNICODE);
}