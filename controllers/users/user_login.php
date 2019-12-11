<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 17:58
 */
require_once __DIR__.'/../../tools/mysql_connection.php';
require_once __DIR__ . '/../../services/user_services.php';
header('Content-Type:application/json');


function user_login($nickname, $password){
    date_default_timezone_set('PRC');
    $res = user_login_service($nickname, $password);
    $type = null;
    if($res != null) {
        $_SESSION['userID'] = $nickname;
        if (strpos($nickname, "S") === 0) $type = "student";
        if (strpos($nickname, "T") === 0) $type = "teacher";
        if (strpos($nickname, "r") === 0) $type = "admin";
        return json_encode(array('result' => true, 'message' => "登陆成功", 'type' => $type), JSON_UNESCAPED_UNICODE);
    }else{
        return json_encode(array("result"=>false,"message"=>"登录失败，账号或密码错误"),JSON_UNESCAPED_UNICODE);
    }


}
