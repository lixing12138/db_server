<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/8
 * Time: 19:57
 */

require_once __DIR__.'/../../services/teacher_get.php';
header('Content-Type:application/json');
session_start();

if(isset($_SESSION['userID'])){
    echo get_teacher_student($_SESSION['userID']);
}else{
    echo  json_encode(array('result'=> false, 'message' => '未登录'),JSON_UNESCAPED_UNICODE);

}