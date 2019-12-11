<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/7
 * Time: 15:03
 */



require_once __DIR__.'/../../../services/submit_application.php';
session_start();

if(isset($_SESSION['userID'])) {
    if(isset($_POST['c_id']) && isset($_POST['reason'])){
        echo submit_application($_SESSION['userID'], $_POST['c_id'], $_POST['reason']);
    } else if(isset($_GET)){
        echo view_application($_SESSION['userID']);
    }else{
        return json_encode(array("result"=>false,'message'=>'请输入正确信息'),JSON_UNESCAPED_UNICODE);
    }


}