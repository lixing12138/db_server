<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 18:07
 */

require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

function user_login_service($nickname, $password){
    $con =get_connection();
    $res = select_table_condition($con, "user", ["u_id"=>$nickname, "u_password"=>$password]);
    $con =null;
    return $res;
}
