<?php

    date_default_timezone_set( 'asia/tokyo' );
    require_once( 'dbconnect.php' );

    $connect = open_db();

    mysqli_query( $connect, 'SET NAMES utf8' );
    mysqli_set_charset( $connect, 'utf8' );

    mysqli_select_db( $connect, '' );

    $res = mysqli_query( $connect, 'select * from info where time>16'. (date('m')-1). date('d') .'00' );
    $json = '[';
    while( $data = mysqli_fetch_array( $res ) ){
        $json = $json. '{locate:"'. $data['locate']. '",time:'. $data['time'] .',flg:'. $data['flg']. '},';
    }
    $json = $json. '{}]';

    echo $json;

    mysqli_close( $connect );

?>