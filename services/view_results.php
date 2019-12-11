<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/7
 * Time: 16:43
 */

require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

function view_results($studentID, $year, $semester){
    $data = array();        //存放每一条记录
    $grade = 0;
    $credit = 0;
    $con = get_connection();
    $sql = 'SELECT distinct course_id,c_title,c_credit, grade   FROM takes, course WHERE takes.course_id = course.c_id and takes.s_id = ? and takes.year = ? and takes.semester = ? and takes.status != 0';
    $res = select_table_params($con, $sql,[$studentID,$year, $semester]);
    if($res != null){
        foreach ($res as $value){
            $course = array();      //课程编号，课程名称，成绩
            $course['course_id'] = $value['course_id'];
            $course['c_title'] = $value['c_title'];
            $course['c_credit'] = $value['c_credit'];
            $course['grade'] = $value['grade'];
            array_push($data, $course);
            $credit += $value['c_credit'];
            $grade += $value['c_credit'] * $value['grade'];
        }
        $result = array();
        $result['total_credit'] = $credit;
        $result['total_grade'] = round($grade / $credit, 2);
        array_push($data, $result);


        return json_encode(array("result"=>true,"message"=>"获取成绩成功", 'data'=>$data),JSON_UNESCAPED_UNICODE);

    }else{
        return json_encode(array("result"=>true,"message"=>"暂无成绩", 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }
}


function view_grades($studentID){
    $data = array();        //存放每一条记录
    $grade = 0;
    $credit = 0;
    $con = get_connection();
    $sql = 'SELECT distinct course_id,c_title,c_credit, grade   FROM takes, course WHERE takes.course_id = course.c_id and takes.s_id = ?  and takes.status != 0';
    $res = select_table_params($con, $sql,[$studentID]);
    if($res != null){
        foreach ($res as $value){
            $course = array();      //课程编号，课程名称，成绩
            $course['course_id'] = $value['course_id'];
            $course['c_title'] = $value['c_title'];
            $course['c_credit'] = $value['c_credit'];
            $course['grade'] = $value['grade'];
            array_push($data, $course);
            $credit += $value['c_credit'];
            $grade += $value['c_credit'] * $value['grade'];
        }
        $result = array();
        $result['total_credit'] = $credit;
        $result['total_grade'] = round($grade / $credit, 2);
        array_push($data, $result);


        return json_encode(array("result"=>true,"message"=>"获取成绩成功", 'data'=>$data),JSON_UNESCAPED_UNICODE);

    }else{
        return json_encode(array("result"=>true,"message"=>"暂无成绩", 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }

}