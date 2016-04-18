<?php
require_once("../bootstrap.php");

$now = time();
$from_time = $now - (60 * 60 * 24 * 2);
$query = 'select * from info where time > ' . $from_time . ' AND time < ' . $now . ' order by time desc';
$arr = DB::conn()->rows($query, [$from_time]);

include VIEW_DIR. '/list.php';
