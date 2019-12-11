<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 23:38
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_POST['t_id']) && isset($_POST['t_name']) && isset($_POST['t_dept_name'])){
        echo updata_teacher($_POST['t_id'], $_POST['t_name'], $_POST['t_dept_name']);
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误，更新失败'),JSON_UNESCAPED_UNICODE);
    }
}