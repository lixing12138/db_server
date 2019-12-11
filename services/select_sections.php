<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/7
 * Time: 0:19
 */

require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');


//获取已选课程
function selected_course($studentID){
    $selected_course = array();
    $con = get_connection();
    $sql = 'SELECT DISTINCT (course_id) FROM  takes WHERE s_id = ? and status = 0';
    $res = select_table_params($con, $sql, [$studentID]);
    //$res = select_table_condition($con, 'takes', ['s_id'=>$studentID, 'status'=>0]);
    if($res != null){
        foreach ($res as $value){
            array_push($selected_course, $value['course_id']);
        }

        $con = null;
        return json_encode(array("result"=>true,'message'=>'获取已选课程ID', 'courses'=>$selected_course),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'未选课','courses'=>$selected_course),JSON_UNESCAPED_UNICODE);
    }
}




//获取未选课程
function un_select_courses($studentID){
    $un_selected_course = array();
    $con = get_connection();
    $sql = 'SELECT DISTINCT  (course_id) FROM sections WHERE course_id NOT IN ( SELECT course_id FROM takes WHERE s_id = ? AND status = 0)';
    $res = select_table_params($con, $sql, [$studentID]);

    if($res != null){
        foreach ($res as $value){
            array_push($un_selected_course, $value['course_id']);
        }

        $con = null;
        return json_encode(array("result"=>true,'message'=>'获取未选课程ID', 'courses'=>$un_selected_course),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'未获取','courses'=>$un_selected_course),JSON_UNESCAPED_UNICODE);
    }

}


function get_course($courseID){
    $course = array();      //存放课程信息
    $time = array();
    $exam = array();
    $con = get_connection();
    $sql = 'SELECT * FROM sections , course WHERE sections.course_id = course.c_id and sections.course_id = ?';
    $res = select_table_params($con, $sql, [$courseID]);
    $sql2 = 'SELECT * FROM sections , teacher WHERE  sections.course_id = ? and sections.t_id = teacher.t_id';
    $res2 = select_table_params($con, $sql2, [$courseID]);
    $sql3 = 'SELECT * FROM examination WHERE  course_id = ? ';
    $res3 = select_table_params($con, $sql3, [$courseID]);
    if($res != null){
        $course['c_name'] = $res[0]['c_title'];
        $course['c_dept_name'] = $res[0]['c_dept_name'];
        $course['c_teacher'] = $res2[0]['t_name'];
        $course['credit'] = $res[0]['c_credit'];
        foreach ($res as $value){
            $c_time = array();
            $c_time['c_sec_time'] = $value['sec_id'];
            $c_time['c_start_time'] = $value['start_time'];
            $c_time['c_end_time'] = $value['end_time'];
            array_push($time, $c_time);
        }
        $course['c_time'] = $time;
        $course['c_building'] = $res[0]['building'];
        $course['c_room_number'] = $res[0]['room_number'];
        $course['c_exam_format'] = $res3[0]['exam_format'];
        $course['c_exam_building'] = $res3[0]['building'];
        $course['c_exam_room'] = $res3[0]['room_number'];
        $course['c_exam_start_time'] = $res3[0]['start_time'];
        $course['c_exam_end_time'] = $res3[0]['end_time'];
        $course['c_total_number'] = $res[0]['total_number'];
        $course['c_max_number'] = $res[0]['max_number'];
        return json_encode(array("result"=>true,'message'=>'请求成功','data'=>$course),JSON_UNESCAPED_UNICODE);
    }else{
        return json_encode(array("result"=>false,'message'=>'请求失败', 'data'=>$course),JSON_UNESCAPED_UNICODE);
    }






















}

