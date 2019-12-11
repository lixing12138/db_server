<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/8
 * Time: 15:56
 */

require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

function get_teacher_courses($teacherID){

    $data = array();
    $con = get_connection();
    $sql = 'SELECT DISTINCT course_id FROM sections WHERE  t_id = ?';
    $res = select_table_params($con, $sql, [$teacherID]);
//    $sql1 = 'SELECT * FROM sections s, course c WHERE  s.t_id = ? and c.c_id = s.course_id';

    if($res != null){
        foreach ($res as $value){
            $sql = 'SELECT * FROM sections , course WHERE sections.course_id = course.c_id and sections.course_id = ?';
            $res1 = select_table_params($con, $sql, [$value['course_id']]);
            if($res1 != null) {
                $time = array();
                $course['course_id'] = $value['course_id'];
                $course['c_dept_name'] = $res1[0]['c_dept_name'];
                $course['c_name'] = $res1[0]['c_title'];
                $course['credit'] = $res1[0]['c_credit'];
                foreach ($res1 as $value1) {
                    $c_time = array();
                    $c_time['c_sec_time'] = $value1['sec_id'];
                    $c_time['c_start_time'] = $value1['start_time'];
                    $c_time['c_end_time'] = $value1['end_time'];
                    array_push($time, $c_time);
                }
                $course['c_time'] = $time;
                $course['c_building'] = $res1[0]['building'];
                $course['c_room_number'] = $res1[0]['room_number'];
                $course['c_total_number'] = $res1[0]['total_number'];
                $course['c_max_number'] = $res1[0]['max_number'];
                array_push($data, $course);
            }



//            $course = array();
//            $course['course_id'] = $value['course_id'];
//            $course['c_title'] = $value['c_title'];
//            $course['building'] = $value['building'];
//            $course['room_number'] = $value['room_number'];
//            $course['sec_id'] = $value['sec_id'];
//            $course['start_time'] = $value['start_time'];
//            $course['end_time'] = $value['end_time'];
//            $course['c_credit'] = $value['c_credit'];


        }
        $con = null;
        return json_encode(array('result'=> true, 'message' => '获取成功', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array('result'=> false, 'message' => '未授课，获取信息失败', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }
}




function get_teacher_student($teacherID){
    $data = array();
    $con = get_connection();
    $sql = 'SELECT * FROM student WHERE  s_id in (SELECT t.s_id FROM sections s, course c, takes t WHERE  s.t_id = ? and c.c_id = s.course_id = t.course_id)';

    $res = select_table_params($con, $sql, [$teacherID]);
    if($res != null){
        foreach ($res as $value){
            $course = array();
            $course['s_id'] = $value['s_id'];
            $course['s_name'] = $value['s_name'];
            $course['s_dept_name'] = $value['s_dept_name'];
            $course['s_class'] = $value['s_class'];
            array_push($data, $course);
        }
        $con = null;
        return json_encode(array('result'=> true, 'message' => '获取成功', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array('result'=> false, 'message' => '未授课，获取信息失败', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }
}


function look_applications($teacherID){
    $data = array();
    $con = get_connection();
    $sql = 'SELECT * FROM sections s ,application a WHERE  a.course_id = s.course_id and s.t_id = ? and status = "待审批"';
    $res = select_table_params($con, $sql, [$teacherID]);
    if($res != null){
        foreach ($res as $value){
            $course = array();
            $course['id'] = $value['id'];
            $course['course_id'] = $value['course_id'];
            $course['s_id'] = $value['s_id'];
            $course['reason'] = $value['reason'];
            $course['status'] = $value['status'];
            array_push($data, $course);
        }
        $con = null;
        return json_encode(array('result'=> true, 'message' => '获取成功', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array('result'=> false, 'message' => '未授课，获取信息失败', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }
}


function deal_application($teacherID, $id, $status){
    $con = get_connection();
    $res = select_table_condition_single($con, 'application',['id'=>$id]);

    if($status === '通过'){
        $res2 = select_table_condition($con,'sections',['course_id'=>$res['course_id']]);

        //审核通过， 修改申请状态， 添加学生选课，修改选课人数
        update_table($con, 'application', ['status'=>'通过'], 'id', $id);
        foreach ($res2 as $value){
            insert_table($con, 'takes', ['s_id'=>$res['s_id'], 'course_id'=>$res['course_id'], 'sec_id'=>$value['sec_id'], 'semester'=>$value['semester'], 'year'=>$value['year'], 'grade'=>0]);
            update_table($con,'sections', ['total_number'=>($value['total_number']+1) ],'course_id',$res['course_id']);
        }

        //更新后在查询判断人数
        $res3 = select_table_condition_single($con,'sections',['course_id'=>$res['course_id']]);
        //查询课程教室人数
        $sql = 'SELECT total_number , c_capacity FROM sections , classroom WHERE course_id = ? and sections.room_number = classroom.c_room_number';
        $res4 = select_table_params($con, $sql, [$res['course_id']]);
        if($res4 != null){
            foreach ($res4 as $value4){
                var_dump($value4);
                if($value4["total_number"] >= $value4["c_capactiy"]){
                    $sql="UPDATE application SET status = '未通过' WHERE course_id ='".$res['course_id']."' and status = '待审批'";
                    $stmt = $con->prepare($sql);
                    $stmt->execute();
                    //update_table($con, 'application', ['status'=>'未通过'], 'course_id', $res['course_id']);
                    return json_encode(array("result"=>false,'message'=>'上课教室人满，提交申请失败'),JSON_UNESCAPED_UNICODE);
                }
            }
        }



        return json_encode(array("result"=>true,'message'=>'处理成功'),JSON_UNESCAPED_UNICODE);
    } else{
        update_table($con, 'application', ['status'=>'未通过'], 'id', $id);
        return json_encode(array("result"=>true,'message'=>'处理成功'),JSON_UNESCAPED_UNICODE);
    }

}