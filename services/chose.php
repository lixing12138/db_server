<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 19:56
 */
require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');
function chose_sections($student_id, $section_id){
    $con =get_connection();
    $res = select_table_condition($con,'sections',['course_id'=>$section_id]);
    //学生选上的课程
    $sql = 'SELECT * FROM takes , examination  WHERE s_id = ? and status = 0 and takes.course_id = examination.course_id ';
    $exams_res = select_table_params($con, $sql, [$student_id]);
    $sql = 'SELECT * FROM takes , sections  WHERE s_id = ? and status = 0 and takes.course_id = sections.course_id and takes.sec_id = sections.sec_id ';
    $takes_res = select_table_params($con, $sql, [$student_id]);
    $res1 = select_table_condition($con,'examination',['course_id'=>$section_id]);
    $res2 = select_table_condition($con, 'takes', ['s_id'=>$student_id, 'course_id'=>$section_id]);
    if($res != null){


        if($res2 != null){
            return json_encode(array("result"=>false,"message"=>"此课程已选，重复选课失败，"),JSON_UNESCAPED_UNICODE);
        }

        //上课/考试 冲突检查
        foreach ($res as $x){       //待选课程
            foreach ($takes_res as $y ){        //已选课程
                if( ($x['sec_id'] === $y['sec_id']) && !(  ($y['end_time'] < $x['start_time']) ||  ($y['start_time'] > $x['end_time']) )){
                    return json_encode(array("result"=>false,"message"=>"选课失败，与已选课程".$y['course_id']."时间冲突"),JSON_UNESCAPED_UNICODE);
                }
            }
        }
        foreach ($res1 as $x){       //待选课程
            foreach ($exams_res as $y ){        //已选课程
                if(!(  ($y['end_time'] < $x['start_time']) ||  ($y['start_time'] > $x['end_time']) )){
                    return json_encode(array("result"=>false,"message"=>"选课失败，与已选课程".$y['course_id']."考试时间冲突"),JSON_UNESCAPED_UNICODE);
                }
            }
        }

        //人数限制
        foreach ($res as $x){
            if($x['total_number'] >= $x['max_number']){
                return json_encode(array("result"=>false,"message"=>"选课失败，课程人数已满"),JSON_UNESCAPED_UNICODE);
            }
        }

        //选课成功
        foreach ($res as $value){
            insert_table($con, 'takes', ['s_id'=>$student_id, 'course_id'=>$section_id, 'sec_id'=>$value['sec_id'], 'semester'=>$value['semester'], 'year'=>$value['year'], 'grade'=>0]);
            update_table($con,'sections', ['total_number'=>($value['total_number']+1) ],'course_id',$section_id);
        }

        $con = null;
        return json_encode(array("result"=>true,"message"=>"选课成功"),JSON_UNESCAPED_UNICODE);
    }else{
        return json_encode(array("result"=>false,"message"=>"选课失败，课程不存在"),JSON_UNESCAPED_UNICODE);
    }
}