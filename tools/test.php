<?php
/**
 * Created by PhpStorm.
 * User: 刘海强
 * Date: 2019/12/6
 * Time: 22:45
 */



require_once __DIR__.'/../services/chose.php';

$a = chose_sections('S0001', 'C001');
$x = json_decode($a, true);
var_dump($x['result']);