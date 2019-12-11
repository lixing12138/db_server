<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 21:08
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    $tmp=file_get_contents("php://input");
    $data=json_decode($tmp,true);
    //echo ($data['s_id'].$data['s_name'].$data['s_dept_name'].$data['s_class'].$data['s_credit'].$data['s_total_credit']);
    if(isset($data['s_id']) && isset($data['s_name']) && isset($data['s_dept_name']) && isset($data['s_class']) && isset($data['s_credit']) && isset($data['s_total_credit'])){
        echo updata_student($data['s_id'], $data['s_name'], $data['s_dept_name'], $data['s_class'],$data['s_credit'], $data['s_total_credit']);
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误，更新失败'),JSON_UNESCAPED_UNICODE);
    }
}