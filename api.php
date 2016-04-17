<?php

header('Content-Type: application/json');

date_default_timezone_set('asia/tokyo');
require_once('dbconnect.php');

$connect = open_db();

mysqli_query($connect, 'SET NAMES utf8');
mysqli_set_charset($connect, 'utf8');

mysqli_select_db($connect, '');
$end = $_GET['map_end'];
$from_time = $_GET['map_start'];

$query = 'select * from info where time > ' . $from_time . ' AND time < ' . $end;

$res = mysqli_query($connect, $query);

function json_safe_encode($data)
{
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

$arr = array();

while ($data = mysqli_fetch_array($res)) {
    $arr[] = $data;
}

$json = json_safe_encode($arr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
//error_log($json);

mysqli_close($connect);

echo $json;