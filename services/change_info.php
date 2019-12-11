<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/8
 * Time: 14:11
 */

require_once __DIR__.'/../tools/mysql_connection.php';
header('Content-Type:application/json');

//学生事务

function add_student($s_id, $s_name, $s_dept_name, $s_class){
    $con =  get_connection();
    $res = select_table_condition($con, 'student', ['s_id'=>$s_id]);
    if($res != null) {
        return json_encode(array("result"=>false,"message"=>$s_id.'已存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        insert_table($con, 'student',['s_id'=>$s_id, 's_name'=>$s_name, 's_dept_name'=>$s_dept_name, 's_class'=>$s_class]);
    }

    $con = null;
    return json_encode(array("result"=>true,"message"=>'添加成功'),JSON_UNESCAPED_UNICODE);
}


function updata_student($s_id, $s_name, $s_dept_name, $s_class, $s_credit, $s_total_credit){
    $con =  get_connection();
    $res = select_table_condition($con, 'student', ['s_id'=>$s_id]);
    if($res == null) {
        return json_encode(array("result"=>false,"message"=>$s_id.'学生不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        update_table($con, 'student', ['s_id'=>$s_id, 's_name'=>$s_name, 's_dept_name'=>$s_dept_name, 's_class'=>$s_class, 's_credit'=>$s_credit, 's_total_credit'=>$s_total_credit], 's_id', $s_id);
    }

    $con = null;
    return json_encode(array("result"=>true,"message"=>'更新成功'),JSON_UNESCAPED_UNICODE);
}

function view_student($s_id){
    $data = array();
    $con =  get_connection();
    $res = select_table_condition_single($con, 'student', ['s_id'=>$s_id]);
    if($res != null) {
        $data['s_id'] = $res['s_id'];
        $data['s_name'] = $res['s_name'];
        $data['s_dept_name'] = $res['s_dept_name'];
        $data['s_class'] = $res['s_class'];
        $data['s_credit'] = $res['s_credit'];
        $data['s_total_credit'] = $res['s_total_credit'];
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$s_id.'学生不存在，请核对信息重新输入', "data"=>$data),JSON_UNESCAPED_UNICODE);
    }
}

function view_students(){
    $message = array();
    $con =  get_connection();
    $value = select_table_condition($con, 'student',[]);
    if($value != null) {
        foreach ($value as $res){
            $data = array();
            $data['s_id'] = $res['s_id'];
            $data['s_name'] = $res['s_name'];
            $data['s_dept_name'] = $res['s_dept_name'];
            $data['s_class'] = $res['s_class'];
            $data['s_credit'] = $res['s_credit'];
            $data['s_total_credit'] = $res['s_total_credit'];
            array_push($message, $data);
        }
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$message),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'查询失败', "data"=>$message),JSON_UNESCAPED_UNICODE);
    }
}

function delete_student($s_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'student', ['s_id'=>$s_id]);
    if($res != null) {

        //删除该学生所选课程
        $res1 = select_table_condition($con, 'takes', ['s_id'=>$s_id]);
        foreach ($res1 as $values){
            quit_section($s_id, $values['course_id']);
        }

        //删除申请
        delete_table($con, 'application', 's_id', $s_id);


        //删除学生
        delete_table($con, 'student', 's_id', $s_id);
        $con = null;
        return json_encode(array("result"=>true,"message"=>'删除成功'),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$s_id.'学生不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }
}


//教师事务


function add_teacher($t_id, $t_name, $t_dept_name){
    $con =  get_connection();
    $res = select_table_condition($con, 'teacher', ['t_id'=>$t_id]);
    if($res != null) {
        return json_encode(array("result"=>false,"message"=>$t_id.'已存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        insert_table($con, 'teacher',['t_id'=>$t_id, 't_name'=>$t_name, 't_dept_name'=>$t_dept_name]);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'添加成功'),JSON_UNESCAPED_UNICODE);
}


function updata_teacher($t_id, $t_name, $t_dept_name){
    $con =  get_connection();
    $res = select_table_condition($con, 'teacher', ['t_id'=>$t_id]);
    if($res == null) {
        return json_encode(array("result"=>false,"message"=>$t_id.'教师不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        update_table($con, 'teacher', ['t_id'=>$t_id, 't_name'=>$t_name, 't_dept_name'=>$t_dept_name], 't_id', $t_id);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'更新成功'),JSON_UNESCAPED_UNICODE);
}

function view_teacher($t_id){
    $data = array();
    $con =  get_connection();
    $res = select_table_condition_single($con, 'teacher', ['t_id'=>$t_id]);
    if($res != null) {
        $data['t_id'] = $res['t_id'];
        $data['t_name'] = $res['t_name'];
        $data['t_dept_name'] = $res['t_dept_name'];
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$t_id.'教师不存在，请核对信息重新输入', "data"=>$data),JSON_UNESCAPED_UNICODE);
    }
}

function view_teachers(){
    $message = array();
    $con =  get_connection();
    $value = select_table_condition($con, 'teacher',[]);
    if($value != null) {
        foreach ($value as $res){
            $data = array();
            $data['t_id'] = $res['t_id'];
            $data['t_name'] = $res['t_name'];
            $data['t_dept_name'] = $res['t_dept_name'];
            array_push($message, $data);
        }
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$message),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'查询失败', "data"=>$message),JSON_UNESCAPED_UNICODE);
    }
}

function delete_teacher($t_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'teacher', ['t_id'=>$t_id]);
    if($res != null) {

        //删除教师
        delete_table($con, 'teacher', 't_id', $t_id);
        $con = null;
        return json_encode(array("result"=>true,"message"=>'删除成功'),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$t_id.'教师不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }
}


//课程事务

function add_course($c_id, $c_title, $c_dept_name, $c_credit){
    $con =  get_connection();
    $res = select_table_condition($con, 'course', ['c_id'=>$c_id]);
    if($res != null) {
        return json_encode(array("result"=>false,"message"=>$c_id.'已存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        insert_table($con, 'course',['c_id'=>$c_id, 'c_title'=>$c_title, 'c_dept_name'=>$c_dept_name,'c_credit'=>$c_credit]);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'添加成功'),JSON_UNESCAPED_UNICODE);
}


function updata_course($c_id, $c_title, $c_dept_name, $c_credit){
    $con =  get_connection();
    $res = select_table_condition($con, 'course', ['c_id'=>$c_id]);
    if($res == null) {
        return json_encode(array("result"=>false,"message"=>$c_id.'不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        update_table($con, 'course', ['c_id'=>$c_id, 'c_title'=>$c_title, 'c_dept_name'=>$c_dept_name, 'c_credit'=>$c_credit], 'c_id', $c_id);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'更新成功'),JSON_UNESCAPED_UNICODE);
}

function view_course($c_id){
    $data = array();
    $con =  get_connection();
    $res = select_table_condition_single($con, 'course', ['c_id'=>$c_id]);
    if($res != null) {
        $data['c_id'] = $res['c_id'];
        $data['c_title'] = $res['c_title'];
        $data['c_dept_name'] = $res['c_dept_name'];
        $data['c_credit'] = $res['c_credit'];
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$c_id.'教师不存在，请核对信息重新输入', "data"=>$data),JSON_UNESCAPED_UNICODE);
    }
}

function view_courses(){
    $message = array();
    $con =  get_connection();
    $value = select_table_condition($con, 'course',[]);
    if($value != null) {
        foreach ($value as $res){
            $data = array();
            $data['c_id'] = $res['c_id'];
            $data['c_title'] = $res['c_title'];
            $data['c_dept_name'] = $res['c_dept_name'];
            $data['c_credit'] = $res['c_credit'];
            array_push($message, $data);
        }
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$message),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'查询失败', "data"=>$message),JSON_UNESCAPED_UNICODE);
    }
}

function delete_course($c_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'course', ['c_id'=>$c_id]);
    if($res != null) {

        //删除课程
        delete_table($con, 'course', 'c_id', $c_id);
        $con = null;
        return json_encode(array("result"=>true,"message"=>'删除成功'),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$c_id.'教师不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }
}


//开课事务

function add_section($course_id, $sec_id, $semester, $year,$building,$room_number,
        $start_time,$end_time,$total_number,$max_number,$t_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'sections', ['course_id'=>$course_id]);
    if($res != null) {
        return json_encode(array("result"=>false,"message"=>$course_id.'已存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{

        //调整时间格式
        $start = date_create($start_time);
        $end = date_create($end_time);
        $start_time = date_format($start, 'H:i:s');
        $end_time = date_format($end, 'H:i:s');

            //检查同一教室的时间冲突
            $res = select_table_condition($con, 'sections', ['room_number'=>$room_number] );
            foreach ($res as $value) {
                if (($value['sec_id'] === $sec_id) && !(($value['end_time'] < $start_time) || ($value['start_time'] > $end_time))) {
                    return json_encode(array("result"=>true,"message"=>'课程' . $course_id . '与' . $value['course_id'] . '---时间冲突（同一时间同一地点有两节课），导入课程失败！)'),JSON_UNESCAPED_UNICODE);
                }
            }


            //教师冲突
            $res = select_table_condition($con, 'sections', ['t_id'=>$t_id] );
            foreach ($res as $value){
                if( ($value['sec_id'] === $sec_id) && (!(($value['end_time'] < $start_time) || ($value['start_time'] > $end_time)))){
                    if($value['room_number'] != $room_number){
                        return json_encode(array("result"=>true,"message"=>'课程' . $course_id . '与' . $value['course_id'] . '---教师冲突（同一教师同一时间有两节课），导入课程失败！'),JSON_UNESCAPED_UNICODE);
                    }
                }
            }

            //没有冲突插入数据库
        insert_table($con, 'sections',['course_id'=>$course_id, 'sec_id'=>$sec_id, 'semester'=>$semester, 'year'=>$year,
            'building'=>$building, 'room_number'=>$room_number, 'start_time'=>$start_time, 'end_time'=>$end_time, 'total_number'=>$total_number,
            'max_number'=>$max_number, 't_id'=>$t_id]);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'添加成功'),JSON_UNESCAPED_UNICODE);
}


function updata_section($course_id, $sec_id, $semester, $year,$building,$room_number,
                        $start_time,$end_time,$total_number,$max_number,$t_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'sections', ['course_id'=>$course_id, 'sec_id'=>$sec_id]);
    if($res == null) {
        return json_encode(array("result"=>false,"message"=>$course_id.'不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }else{
        update_table($con, 'sections',  ['course_id'=>$course_id, 'sec_id'=>$sec_id, 'semester'=>$semester, 'year'=>$year,
            'building'=>$building, 'room_number'=>$room_number, 'start_time'=>$start_time, 'end_time'=>$end_time, 'total_number'=>$total_number,
            'max_number'=>$max_number, 't_id'=>$t_id], 'course_id', $course_id);
    }
    $con = null;
    return json_encode(array("result"=>true,"message"=>'更新成功'),JSON_UNESCAPED_UNICODE);
}

function view_section($course_id){
    $course = array();      //存放课程信息
    $time = array();
    $con =  get_connection();
    $res = select_table_condition($con, 'sections', ['course_id'=>$course_id]);
    if($res != null) {
        $course['course_id'] = $res[0]['course_id'];
        $course['semester'] = $res[0]['semester'];
        $course['year'] = $res[0]['year'];
        $course['building'] = $res[0]['building'];
        $course['room_number'] = $res[0]['room_number'];
        $course['total_number'] = $res[0]['total_number'];
        $course['max_number'] = $res[0]['max_number'];
        foreach ($res as $value){
                $c_time = array();
                $c_time['sec_id'] = $value['sec_id'];
                $c_time['start_time'] = $value['start_time'];
                $c_time['end_time'] = $value['end_time'];
                array_push($time, $c_time);
            }
        $course['time'] = $time;
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$course),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$course_id.'不存在，请核对信息重新输入', "data"=>$course),JSON_UNESCAPED_UNICODE);
    }
}

function view_sections(){
    $data = array();
    $con =  get_connection();
    $sql = 'SELECT DISTINCT course_id FROM sections ';
    $res = select_table_params($con, $sql, []);
    if($res != null){
        foreach ($res as $value){
            $course = array();
            $time = array();
            $res1 = select_table_condition($con, 'sections', ['course_id'=>$value['course_id']]);
            $course['course_id'] = $res1['course_id'];
            $course['semester'] = $res1['semester'];
            $course['year'] = $res1['year'];
            $course['building'] = $res1['building'];
            $course['room_number'] = $res1['room_number'];
            $course['total_number'] = $res1['total_number'];
            $course['max_number'] = $res1['max_number'];
            foreach ($res1 as $value1){
                $c_time = array();
                $c_time['sec_id'] = $value1['sec_id'];
                $c_time['start_time'] = $value1['start_time'];
                $c_time['end_time'] = $value1['end_time'];
                array_push($time, $c_time);
            }
            $course['time'] = $time;
            $course['t_id'] = $res1['t_id'];
            array_push($data, $course);
        }
        $con = null;
        return json_encode(array("result"=>true,"message"=>"信息查询成功", "data"=>$data),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>'不存在，请核对信息重新输入', "data"=>$data),JSON_UNESCAPED_UNICODE);
    }
}

function delete_section($c_id){
    $con =  get_connection();
    $res = select_table_condition($con, 'sections', ['course_id'=>$c_id]);
    if($res != null) {

        //删除课程
        delete_table($con, 'sections', 'course_id', $c_id);
        $con = null;
        return json_encode(array("result"=>true,"message"=>'删除成功'),JSON_UNESCAPED_UNICODE);
    }else{
        $con = null;
        return json_encode(array("result"=>false,"message"=>$c_id.'教师不存在，请核对信息重新输入'),JSON_UNESCAPED_UNICODE);
    }
}

