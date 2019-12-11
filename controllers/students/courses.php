<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 19:48
 */

require_once __DIR__.'/../../services/chose.php';
require_once __DIR__.'/../../services/quit.php';
require_once __DIR__.'/../../services/select_sections.php';
session_start();

if(isset($_SESSION['userID'])) {
    if (isset($_POST['operation']) && isset($_POST['c_id'])) {
        if ($_POST['operation'] === 'chose') {
            echo chose_sections($_SESSION['userID'], $_POST['c_id']);
        } else if ($_POST['operation'] === 'quit') {
            echo quit_section($_SESSION['userID'], $_POST['c_id']);
        }
    } else if(isset($_GET['choose'])){
        if($_GET['choose'] === 'true'){
            //获取已选课程
            echo selected_course($_SESSION['userID']);
        }else{
            //获取未选课程
            echo un_select_courses($_SESSION['userID']);

        }
    }else if(isset($_GET['c_id'])){ //获取课程信息
        echo get_course($_GET['c_id']);
    }else{
        echo json_encode(array("result"=>false,"message"=>"填写信息错误"),JSON_UNESCAPED_UNICODE);
    }
}

