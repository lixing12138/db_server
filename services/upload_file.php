<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/6
 * Time: 10:57
 */
require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

function upload_student_file($filename){

    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'student', ['s_id'=>$data[0]]);
            if($res != null) {
                array_push($error_data, '用户'.$data[0].'已存在导入失败！');
            }else{
                insert_table($con, 'student',['s_id'=>$data[0], 's_name'=>$data[1], 's_dept_name'=>$data[2], 's_class'=>$data[3]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>false,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }

}


function  upload_teacher_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h, 1000)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'teacher', ['t_id'=>$data[0]] );
            if($res != null) {
                array_push($error_data, '用户'.$data[0].'已存在导入失败！');
            }else{
                insert_table($con, 'teacher',['t_id'=>$data[0], 't_name'=>$data[1], 't_dept_name'=>$data[2]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>false,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }
}


function upload_courses_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h, 1000)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'course', ['c_id'=>$data[0]] );
            if($res != null) {
                array_push($error_data, '课程'.$data[0].'已存在导入失败！');
            }else{
                insert_table($con, 'course',['c_id'=>$data[0], 'c_title'=>$data[1], 'c_dept_name'=>$data[2], 'c_credit'=>$data[3]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>false,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }

}



function upload_sections_file($filename){
    $error_data = array('导入数据成功！');
    $size_error_data = count($error_data);
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h, 1000)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);

            //调整时间格式
            $start = date_create($data[6]);
            $end = date_create($data[7]);
            $start_time = date_format($start, 'H:i:s');
            $end_time = date_format($end, 'H:i:s');

            //时间冲突
            $res = select_table_condition($con, 'sections', ['course_id'=>$data[0],'sec_id'=>$data[1]]);
            if($res != null) {
                array_push($error_data, '课程'.$data[0].'已存在导入失败！');
            }else{
                //检查同一教室的时间冲突
                $res = select_table_condition($con, 'sections', ['room_number'=>$data[5]] );
                foreach ($res as $value) {
                    if (($value['sec_id'] === $data[1]) && !(($value['end_time'] < $start_time) || ($value['start_time'] > $end_time))) {
                        array_push($error_data, '课程' . $data[0] . '与' . $value['course_id'] . '---时间冲突（同一时间同一地点有两节课），导入课程失败！');
                    }
                }

                if(count($error_data) != $size_error_data){
                    $size_error_data = count($error_data);
                    continue;
                }

                //教师冲突
                $res = select_table_condition($con, 'sections', ['t_id'=>$data[10]] );
                foreach ($res as $value){
                    if( ($value['sec_id'] === $data[1]) && (!(($value['end_time'] < $start_time) || ($value['start_time'] > $end_time)))){
                        if($value['room_number'] != $data[5]){
                            array_push($error_data, '课程'.$data[0].'与'.$value['course_id'].'---教师冲突（同一教师同一时间有两节课），导入课程失败！');
                        }
                    }
                }
                if(count($error_data) != $size_error_data){
                    $size_error_data = count($error_data);
                    continue;
                }

                //没有冲突插入数据库
                insert_table($con, 'sections',['course_id'=>$data[0], 'sec_id'=>$data[1], 'semester'=>$data[2], 'year'=>$data[3], 'building'=>$data[4], 'room_number'=>$data[5], 'start_time'=>$data[6], 'end_time'=>$data[7], 'total_number'=>$data[8], 'max_number'=>$data[9], 't_id'=>$data[10]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>false,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }
}


function upload_exam_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h)) != FALSE){
            $data = preg_split("/[,\r\n]+/", $data);
            $res = select_table_condition($con, 'examination', ['course_id'=>$data[0]]);
            if($res != null) {
                array_push($error_data, '考试信息'.$data[0].'已存在导入失败！');
            }else{
                var_dump($data);
                insert_table($con, 'examination',['course_id'=>$data[0], 'sec_id'=>$data[1], 'semester'=>$data[2], 'year'=>$data[3], 'start_time'=>$data[4], 'end_time'=>$data[5], 'building'=>$data[6], 'room_number'=>$data[7], 'exam_format'=>$data[8]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>true,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }

}


function upload_classroom_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'classroom', ['c_room_number'=>$data[1]]);
            if($res != null) {
                array_push($error_data, '考试信息'.$data[0].'已存在导入失败！');
            }else{
                insert_table($con, 'classroom',['c_building'=>$data[0], 'c_room_number'=>$data[1], 'c_capacity'=>$data[2]]);
            }
        }
        $con = null;
        return json_encode(array("result"=>true,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }
}


function  upload_user_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'user', ['u_id'=>$data[0]]);
            if($res != null) {
                array_push($error_data, '用户'.$data[0].'已存在导入失败！');
            }else{
                insert_table($con, 'user',['u_id'=>$data[0], 'u_password'=>$data[1] ]);
            }
        }
        $con = null;
        return json_encode(array("result"=>true,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }
}


//教师登成绩
function upload_grade_file($filename){
    $error_data = array('导入数据成功！');
    $message = null;
    $con =  get_connection();
    if(($h = fopen($filename, 'r')) != FALSE){
        fgets($h);
        while (($data = fgets($h)) != FALSE){
            $data = preg_split("/[\s,]+/", $data);
            $res = select_table_condition($con, 'takes', ['s_id'=>$data[0], 'course_id'=>$data[1], 'semester'=>$data[2], 'year'=>$data[3], 'status'=>1]);
            if($res != null) {
                array_push($error_data, '用户'.$data[0].'成绩已存在导入失败！');
            }else{
                $sql="UPDATE takes SET grade = ".$data[4]." , status = 1 WHERE s_id = '".$data[0]."' and course_id = '".$data[1]."' and semester ='".$data[2]."' and  year ='".$data[3]."'";
                $stmt = $con->prepare($sql);
                $stmt->execute();

                //insert_table($con, 'user',['u_id'=>$data[0], 'u_password'=>$data[1] ]);
            }
        }
        $con = null;
        return json_encode(array("result"=>true,"data"=>$error_data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>"导入文件失败"),JSON_UNESCAPED_UNICODE);
    }
}


//手动登成绩
function upload_grade($s_id,$course_id,$semester,$year,$grade){
    $con =  get_connection();
    $res = select_table_condition($con, 'takes', ['s_id'=>$s_id, 'course_id'=>$course_id, 'semester'=>$semester, 'year'=>$year, 'status'=>1]);
    if($res != null) {
        $con = null;
        return json_encode(array("result"=>false,"message"=>'用户'.$s_id.'成绩已存在导入失败！'),JSON_UNESCAPED_UNICODE);
    }else{
        $sql="UPDATE takes SET grade = ".$grade." , status = 1 WHERE s_id = '".$s_id."' and course_id = '".$course_id."' and semester ='".$semester."' and  year ='".$year."'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $con = null;
        return json_encode(array("result"=>true,"message"=>"上传成绩成功"),JSON_UNESCAPED_UNICODE);
    }
}

