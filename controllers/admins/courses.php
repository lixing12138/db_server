<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 23:52
 */

require_once __DIR__.'/../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){

    //增加课程信息
    if(isset($_POST['c_id']) && isset($_POST['c_title']) && isset($_POST['c_dept_name']) && isset($_POST['c_credit'])){
        echo add_course($_POST['c_id'], $_POST['c_title'], $_POST['c_dept_name'], $_POST['c_credit']);
    }else if(isset($_GET['c_id'])){//查找
        if($_GET['c_id'] == null){
            //返回所有信息
            echo view_courses();
        }else{
            //返回一个学生信息
            echo  view_course($_GET['c_id']);
        }
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误'),JSON_UNESCAPED_UNICODE);
    }

}