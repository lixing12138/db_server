<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/10
 * Time: 1:00
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_POST['course_id']) && isset($_POST['sec_id']) && isset($_POST['semester']) && isset($_POST['year']) &&
        isset($_POST['building']) && isset($_POST['room_number']) && isset($_POST['start_time']) &&
        isset($_POST['end_time']) && isset($_POST['total_number']) && isset($_POST['max_number']) &&
        isset($_POST['t_id'])){
        echo updata_section($_POST['course_id'], $_POST['sec_id'], $_POST['semester'], $_POST['year'],$_POST['building'],$_POST['room_number'],
            $_POST['start_time'],$_POST['end_time'],$_POST['total_number'],$_POST['max_number'],$_POST['t_id']);
    }
}