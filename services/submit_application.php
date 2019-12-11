<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/7
 * Time: 15:27
 */


require_once __DIR__.'/../tools/mysql_connection.php';
require_once __DIR__.'/../services/chose.php';
header('Content-Type:application/json');

function submit_application($studentID, $courseID, $reason){
    $con = get_connection();
    $res = select_table_condition($con,'takes',['s_id'=>$studentID, 'course_id'=>$courseID]);
    //若选课则不能提交申请
    if($res != null){
        return json_encode(array("result"=>false,'message'=>'已选课提交申请失败'),JSON_UNESCAPED_UNICODE);
    }else{

        //课程有余量不能提交申请
        $sql = 'SELECT total_number, max_number FROM sections WHERE course_id = ?';
        $res = select_table_params($con, $sql, [$courseID]);
        if($res != null){
            foreach ($res as $value){
                if($value['total_number'] < $value['max_number']){
                    return json_encode(array("result"=>false,'message'=>'课程有余量，提交申请失败'),JSON_UNESCAPED_UNICODE);
                }
            }
        }

        //申请课程教室人满
        $sql = 'SELECT total_number , c_capacity FROM sections , classroom WHERE course_id = ? and sections.building = classroom.c_room_number';
        $res = select_table_params($con, $sql, [$courseID]);
        if($res != null){
            foreach ($res as $value){
                if($value['total_number'] >= $value['c_capactiy']){
                    return json_encode(array("result"=>false,'message'=>'上课教室人满，提交申请失败'),JSON_UNESCAPED_UNICODE);
                }
            }
        }


        //已提交该课程申请
        $sql = 'SELECT * FROM application WHERE course_id = ? and s_id = ?';
        $res = select_table_params($con, $sql, [$courseID, $studentID]);
        if($res != null){
            return json_encode(array("result"=>false,'message'=>'已提交该课程申请'),JSON_UNESCAPED_UNICODE);
        }

        //已退课程不能申请
        $sql = 'SELECT * FROM quits WHERE course_id = ? and s_id = ?';
        $res = select_table_params($con, $sql, [$courseID, $studentID]);
        if($res != null){
            return json_encode(array("result"=>false,'message'=>'已退课程不能申请，申请失败'),JSON_UNESCAPED_UNICODE);
        }

        //与已选课程有冲突不能提交
        $x = json_decode(chose_sections($studentID,$courseID), true);
        if(!$x['result']){
            return json_encode(array("result"=>false,'message'=>$x['message'].' 申请失败'),JSON_UNESCAPED_UNICODE);
        }

        //提交申请
        $res = select_table_condition_single($con, 'sections', ['course_id'=>$courseID]);
        if($res != null) {
            insert_table($con, 'application', ['course_id' => $courseID,  'semester' => $res['semester'], 'year' => $res['year'], 's_id' => $studentID, 'reason' => $reason, 'status' => '待审批']);
            return json_encode(array("result"=>true,'message'=>'提交申请成功'),JSON_UNESCAPED_UNICODE);
        }

    }
}



function view_application($studentID){
    $con = get_connection();
    $data = array();

    $sql = 'SELECT * FROM application a , course c WHERE a.s_id = ? and a.course_id = c.c_id';
    $res = select_table_params($con, $sql, [$studentID]);
    if($res != null){
        foreach ($res as $value){
            $application = array();
            $application['c_id'] = $value['c_id'];
            $application['c_title'] = $value['c_title'];
            $application['c_credit'] = $value['c_credit'];
            $application['c_status'] = $value['status'];
            array_push($data, $application);
        }
        return json_encode(array("result"=>true,'message'=>'查看申请成功', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        return json_encode(array("result"=>false,'message'=>'没有申请', 'data'=>$data),JSON_UNESCAPED_UNICODE);
    }











}