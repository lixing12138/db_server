<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/10
 * Time: 0:21
 */

header('Content-Type:application/json');

require_once __DIR__.'/../../../services/change_info.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_GET['c_id'])){
        echo delete_course($_GET['c_id']);
    }
}