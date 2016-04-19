<?php
require_once("../bootstrap.php");

$now = time();
$from_time = $now - (60 * 60 * 24 * 2);
$query = 'SELECT * FROM info';
$rows = DB::conn()->rows('SELECT * FROM info', []);
$r_rows = DB::conn()->rows('SELECT * FROM rousui', []);

$arr = array_merge($r_rows, $rows);

$data = array();
foreach ($arr as $a) {
    $flg_str = "";
    if ($a["flg"] == 0) {
        $flg_str = '<img src="no.png" > 水が出ない';
    } elseif ($a["flg"] == 1) {
        $flg_str = '<img src="ok.png" > 水が出る';
    } elseif ($a["flg"] == 2) {
        $flg_str = '<img src="go.png" > 水の提供可能';
    } elseif ($a["flg"] == 3) {
        $flg_str = '<img src="notdrink.png" > 水出るが飲めない';
    }

    if($a["comment"] == "null"){
        $a["comment"] = "";
    }

    $a["time"] = date("Y/m/d H:i:s", $a["time"]);
    $a["flg_str"] = $flg_str;
    array_push($data, $a);
}

$template = Template::factory();
echo $template->render('list.html', array(
    'data' => $data,
    'now' => $now,
));
