<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 23:25
 */

require_once __DIR__.'/../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){

    //增加教师信息
    if(isset($_POST['t_id']) && isset($_POST['t_name']) && isset($_POST['t_dept_name']) ){
        echo add_teacher($_POST['t_id'], $_POST['t_name'], $_POST['t_dept_name']);
    } else if(isset($_GET['t_id'])){ //查找
        if($_GET['t_id'] == null){
            //返回所有信息
            echo view_teachers();
        }else{
            //返回一个学生信息
            echo  view_teacher($_GET['t_id']);
        }
    }else{
        echo json_encode(array("result"=>false,"message"=>'填写信息错误'),JSON_UNESCAPED_UNICODE);
    }


}