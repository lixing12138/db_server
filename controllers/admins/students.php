<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/8
 * Time: 14:04
 */

require_once __DIR__.'/../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){

    //增加学生信息
    if(isset($_POST['s_id']) && isset($_POST['s_name']) && isset($_POST['s_dept_name']) && isset($_POST['s_class'])){
        echo add_student($_POST['s_id'], $_POST['s_name'], $_POST['s_dept_name'], $_POST['s_class']);
    } else if(isset($_GET['s_id'])){//查找
        if($_GET['s_id'] == null){
            //返回所有信息
            echo view_students();
        }else{
            //返回一个学生信息
            echo  view_student($_GET['s_id']);
        }
    } else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误'),JSON_UNESCAPED_UNICODE);
    }




}
