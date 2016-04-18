<?php
require_once("../bootstrap.php");

if( isset( $_POST['submit'] ) ){
    $err = "";

    $time    = time();
    $flg     = VerifyFlag($_POST['flg']);
    $locate  = $_POST['locate'];
    $comment = $_POST['comment'];
    if (IsLocateString($locate) == false) {
        $err = "経度緯度情報が不正です";
    }

    if ($err === "") {
        $sql = "INSERT INTO info SET time = :time, locate = :locate, flg = :flg, comment = :comment";
        $params = ["time"    => $time ,
                   "locate"  => $locate ,
                   "flg"     => (int)$flg ,
                   "comment" => $comment ,];

        DB::conn()->query($sql , $params);
        header('Location: index.php');
    }
    echo $err .PHP_EOL;
}

include VIEW_DIR . '/post.php';
