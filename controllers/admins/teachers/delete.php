<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 23:48
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_GET['t_id'])){
        echo delete_teacher($_GET['t_id']);
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误，删除失败'),JSON_UNESCAPED_UNICODE);
    }
}