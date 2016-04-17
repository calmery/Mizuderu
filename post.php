<?php

if( isset( $_POST['submit'] ) ){

    $time    = time();
    $flg     = $_POST['flg'];
    $locate  = $_POST['locate'];
    $comment = $_POST['comment'];

    error_log("Post:".$time.",".$flg.",".$locate.",".$commnet);

    $err = '不正な値が入力された可能性があります．投稿に失敗しました．';

    if( $time != '' && $flg != '' && $locate != '' ){

        require_once( 'dbconnect.php' );

        $connect = open_db();
        mysqli_query( $connect, 'SET NAMES utf8' );
        mysqli_set_charset( $connect, 'utf8' );

        mysqli_select_db( $connect, '' );

        if( $comment == '' )
            $comment = 'null';

        $time    = mysqli_real_escape_string( $connect, $time );
        $locate  = mysqli_real_escape_string( $connect, $locate );
        $flg     = mysqli_real_escape_string( $connect, $flg );
        $comment = mysqli_real_escape_string( $connect, $comment );

        $query = "insert into info ( time, locate, flg, comment ) values (". $time .",'".  $locate ."',". $flg .", '". $comment ."');";
        $res = mysqli_query( $connect, $query );

        if( $res ) header( 'Location: index.php' );
        else echo $err. '(01)';

        mysqli_close($connect);


    } else echo $err. '(02)';
}


include 'views/post.php';
