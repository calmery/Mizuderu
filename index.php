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
$query = 'select * from info where time> ' . $from_time . ' order by time asc';
error_log($query);
$res = mysqli_query($connect, $query);

function json_safe_encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 *
 * 日時の切り上げ/切捨て/四捨五入
 *
 * @param string $date
 * @param integer $place 1|10|100|1000|10000|100000
 * @param string $math ceil|floor|round
 * @return string
 */
function getDateRound($date, $place, $math)
{
    list($Y, $m, $d, $H, $i, $s) = explode("-", date('Y-m-d-H-i-s', strtotime($date)));

    // 秒（1で1の位、10で秒全体）
    if ($place == 1) {
        $s = $math($s * 0.1) * 10;
    } elseif ($place > 1) {
        $s = $math($s * 0.01) * 100;
        if ($s > 60) {
            $s = 60;
        }
    }

    if ($place > 10) {
        $date = date('Y-m-d-H-i-s', mktime($H, $i, $s, $m, $d, $Y));
        list($Y, $m, $d, $H, $i, $s) = explode("-", $date);
    }
    // 分（100で1の位、1000で分全体）
    if ($place == 100) {
        $i = $math($i * 0.1) * 10;
    } elseif ($place > 100) {
        $i = $math($i * 0.01) * 100;
        if ($i > 60) {
            $i = 60;
        }
    }

    if ($place > 1000) {
        $date = date('Y-m-d-H-i-s', mktime($H, $i, $s, $m, $d, $Y));
        list($Y, $m, $d, $H, $i, $s) = explode("-", $date);
    }
    // 時間（10000で1の位、100000で時間全体）
    if ($place == 10000) {
        $H = $math($H * 0.1) * 10;
    } elseif ($place > 10000) {
        $H = $math($H * 0.01) * 100;
        if ($H > 24) {
            $H = 24;
        }
    }

    return date('Y-m-d H:i:s', mktime($H, $i, $s, $m, $d, $Y));
}

$arr = array();
while ($data = mysqli_fetch_array($res)) {
//    $json = $json . json_encode($data);
//    //$json = $json. '{locate:"'. $data['locate']. '",time:'. $data['time'] .',flg:'. $data['flg']. '},';
//    $json = $json . ',';
    $arr[] = $data;
}
$from_time = strtotime(getDateRound(date("Y-m-d H:i:s", $arr[0]['time']), 100, "floor"));
$now = strtotime(getDateRound(date("Y-m-d H:i:s", $now), 100, "ceil"));
$json = json_safe_encode($arr);
//error_log($json);

//echo '<pre>';
//print_r($arr);
//exit;

mysqli_close($connect);

include 'views/index.php';

