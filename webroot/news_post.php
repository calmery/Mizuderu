<?php
/**
 * TODO: 使ってるところ見つからなかったのと、DBスキーマ情報がなかったので一旦放置
 * @KeisukeUtsumi
 */


if( isset( $_POST['submit'] ) ){

    $title     = $_POST['title'];
    $url  = $_POST['url'];

    error_log("Post:".$title.",".$url);

    $err = '不正な値が入力された可能性があります．投稿に失敗しました．';

    if( $title != '' && $url != ''){

        require_once('dbconnect.php');

        $connect = open_db();
        mysqli_query( $connect, 'SET NAMES utf8' );
        mysqli_set_charset( $connect, 'utf8' );

        mysqli_select_db( $connect, '' );

        $title    = mysqli_real_escape_string( $connect, $title );
        $url  = mysqli_real_escape_string( $connect, $url );

        $query = "insert into news ( title, url ) values ( '". $title ."','".  $url . "');";

        $res = mysqli_query( $connect, $query );

        if( $res ) header( 'Location: index.php' );
        else echo $err. '(01)';

        mysqli_close($connect);


    } else echo $err. '(02)';
}

include 'views/news_post.php';
