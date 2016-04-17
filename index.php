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
$query = 'select * from info where time>' . $from_time;
error_log($query);
$res = mysqli_query($connect, $query);

function json_safe_encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

$arr = array();
while ($data = mysqli_fetch_array($res)) {
//    $json = $json . json_encode($data);
//    //$json = $json. '{locate:"'. $data['locate']. '",time:'. $data['time'] .',flg:'. $data['flg']. '},';
//    $json = $json . ',';
    $arr[] = $data;
}
$json = json_safe_encode($arr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
error_log($json);

mysqli_close($connect);

include 'views/index.php';

