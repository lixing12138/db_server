<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 21:57
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_GET['s_id'])){
        echo delete_student($_GET['s_id']);
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误，更新失败'),JSON_UNESCAPED_UNICODE);
    }
}