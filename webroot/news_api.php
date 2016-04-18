<?php
require_once("../bootstrap.php");

$rows = DB::conn()->rows("SELECT * FROM news order by created_at LIMIT 0, 5");



header('Content-Type: application/json');

$json = json_safe_encode($rows);
echo $json;
