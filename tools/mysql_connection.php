<?php
require_once __DIR__.'/../config.php';
/**
 *连接数据库
 */
function get_connection(){
    global $sql_host;
    global $sql_user;
    global $sql_password;
    global $sql_db;
    try{
        $con=new PDO('mysql:host=' . $sql_host . ';dbname=' . $sql_db, $sql_user, $sql_password);
        $con->exec("SET NAMES 'utf8mb4'");
        return $con;
    }catch (PDOException $e){
        print $e->getMessage();
        return null;
    }
}

/*
 * 插入数据
 * @param table为表名
 * @param dict key为属性，value为值
 */
function insert_table($con, $table, $dict)
{
    $num = sizeof($dict);
    $sql_header = "INSERT INTO " . $table . "(";
    $sql_tail = ") VALUES (";
    $keys = array_keys($dict);
    for ($i = 0; $i < $num; $i++) {
        if ($i == $num - 1) {
            $sql_tail .= "?";
            $sql_header .= $keys[$i];
        } else {
            $sql_header .= $keys[$i] . ", ";
            $sql_tail .= "?, ";
        }
    }
    $sql = $sql_header . $sql_tail . ")";
    $stmt = $con->prepare($sql);
    for ($i = 0; $i < $num; $i++) {
        $stmt->bindParam($i + 1, $dict[$keys[$i]]);
    }
    return $stmt->execute();
}

/*
 * 更新数据库
 * @param table         为表名
 * @param dict keys          为表中属性名
 * @param dict values        为属性对应值
 * @param cond_key      条件属性
 * @param cond_value    条件值
 */
function update_table($con, $table, $dict, $cond_key, $cond_value)
{
    $num = sizeof($dict);
    $sql_header = "UPDATE " . $table . " SET ";
    $keys = array_keys($dict);
    for ($i = 0; $i < $num; $i++) {
        if ($i == $num - 1)
            $sql_header .= $keys[$i] . "=? ";
        else
            $sql_header .= $keys[$i] . "=?, ";
    }
    $sql = $sql_header . "WHERE " . $cond_key . "=?";
    $stmt = $con->prepare($sql);
    for ($i = 0; $i < $num; $i++) {
        $stmt->bindParam($i + 1, $dict[$keys[$i]]);
    }
    $stmt->bindParam($num + 1, $cond_value);
    return $stmt->execute();
}
/**
 * 使用等于条件进行查找
 *
 */
function select_table_condition($con, $table, $dict)
{
    $num = sizeof($dict);
    $sql = "SELECT * FROM " . $table;
    $keys = array_keys($dict);
    if ($num > 0) {
        $sql .= " WHERE ";
        for ($i = 0; $i < $num; $i++) {
            if ($i == $num - 1)
                $sql .= $keys[$i] . " = ? ";
            else
                $sql .= $keys[$i] . " = ? AND ";
        }
    }
    $stmt = $con->prepare($sql);
    for ($i = 0; $i < $num; $i++) {
        $stmt->bindParam($i + 1, $dict[$keys[$i]]);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/*
 * 等于条件查找单条记录
 * */
function select_table_condition_single($con, $table, $dict)
{
    $res = select_table_condition($con, $table, $dict);
    if (sizeof($res) == 0)
        return null;
    return $res[0];
}
/*
 * 删除表中的值
 * */
function delete_table($con,$table,$key,$value){
    $sql="DELETE FROM ".$table." WHERE ".$key." = '".$value."' ";
    $stmt = $con->prepare($sql);
    return $stmt->execute();
}


/*
 * 自定义sql及参数查找
 */
function select_table_params($con, $sql, $values) {
    $stmt = $con->prepare($sql);
    $num = sizeof($values);
    for ($i = 1; $i <= $num; $i++) {
        $stmt->bindParam($i, $values[$i - 1]);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>