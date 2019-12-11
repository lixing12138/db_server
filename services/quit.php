<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/5
 * Time: 22:32
 */
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

function quit_section($student_id, $section_id){
    $con =get_connection();
    $res2 = select_table_condition($con, 'takes', ['s_id'=>$student_id, 'course_id'=>$section_id]);
    if($res2 != null){
        $res = select_table_condition($con,'sections',['course_id'=>$section_id]);
    //删除课程

        $sql="DELETE FROM  takes WHERE s_id = '".$student_id ."'and course_id = '".$section_id."'";
        $stmt = $con->prepare($sql);
        if($stmt->execute()){
            foreach ($res as $value){
                //课程人数+1
                update_table($con,'sections', ['total_number'=>($value['total_number'] - 1) ],'course_id',$section_id);
            }
            //退课记录
            insert_table($con, 'quits', ['s_id'=>$student_id, 'course_id'=>$section_id]);
            $con = null;
            return json_encode(array("result"=>true,"message"=>"退课成功"),JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array("result"=>false,"message"=>"退课失败请重试"),JSON_UNESCAPED_UNICODE);
        }
    }else{
        return json_encode(array("result"=>false,"message"=>"未选此课程"),JSON_UNESCAPED_UNICODE);
    }
}