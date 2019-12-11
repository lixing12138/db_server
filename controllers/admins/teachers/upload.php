<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/6
 * Time: 18:45
 */
header('Content-Type:application/json');
require_once __DIR__.'/../../../services/upload_file.php';
session_start();

if(isset($_SESSION['userID'])){
    if(isset($_FILES['file'])){
        $tmp = explode('.',$_FILES['file']['name'] );
        $extension = strtolower(end($tmp));
        if($_FILES['file']['error']){
            echo json_encode(array("result"=>false,"message"=>"上传失败，请再试一次"),JSON_UNESCAPED_UNICODE);
        }else{
            if (file_exists(__DIR__."/../../../services/upload/"  . $_FILES["file"]["name"]))
            {
                echo $_FILES["file"]["name"] . " 文件已经存在。 ";
            }
            else
            {
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                $name =iconv("utf-8","gb2312",$_FILES["file"]["name"]);
                if( move_uploaded_file($_FILES["file"]["tmp_name"], __DIR__."/../../../services/upload/"  . $name)){
                    echo upload_teacher_file(__DIR__."/../../../services/upload/"  . $name);
                }

            }

        }
    }
}