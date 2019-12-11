<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/7
 * Time: 16:22
 */

require_once __DIR__.'/../../../services/view_results.php';
require_once __DIR__.'/../../../services/select_sections.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_GET['year']) && isset($_GET['semester'])){
        echo view_results($_SESSION['userID'], $_GET['year'], $_GET['semester']);
    } else{
        //return json_encode(array("result"=>false,'message'=>'请输入正确信息'),JSON_UNESCAPED_UNICODE);
        echo view_grades($_SESSION['userID']);
    }
}