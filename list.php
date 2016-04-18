<?php

date_default_timezone_set('asia/tokyo');
require_once('dbconnect.php');

$connect = open_db();

mysqli_query($connect, 'SET NAMES utf8');
mysqli_set_charset($connect, 'utf8');

mysqli_select_db($connect, '');

$now = time();
$from_time = $now - (60 * 60 * 24 * 2);
error_log($from_time);
$query = 'select * from info where time> ' . $from_time . ' order by time desc';
error_log($query);
$res = mysqli_query($connect, $query);

$arr = array();
while ($data = mysqli_fetch_array($res)) {
    $arr[] = $data;
}


mysqli_close($connect);

include 'views/list.php';