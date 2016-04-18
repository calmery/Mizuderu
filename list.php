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

//foreach($arr as &$a){
//    $query = array("latlng" => $a["locate"]);
//    $res = callApi("GET", "https://maps.googleapis.com/maps/api/geocode/json", $query);
//
//    $a["address"] = $res["results"][0]["formatted_address"];
//}

mysqli_close($connect);

include 'views/list.php';


function callApi($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return json_decode($result, true);
}