<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/9
 * Time: 16:57
 */

require_once __DIR__.'/../../services/teacher_get.php';
header('Content-Type:application/json');
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_POST['id']) && isset($_POST['status'])){
        echo deal_application($_SESSION['userID'], $_POST['id'], $_POST['status']);
    }else{
        echo look_applications($_SESSION['userID']);
    }
}else{

}
