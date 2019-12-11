<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/10
 * Time: 0:08
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_POST['c_id']) && isset($_POST['c_title']) && isset($_POST['c_dept_name']) && isset($_POST['c_credit'])){
        echo updata_course($_POST['c_id'], $_POST['c_title'], $_POST['c_dept_name'], $_POST['c_credit']);
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误，更新失败'),JSON_UNESCAPED_UNICODE);
    }
}