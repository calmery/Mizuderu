<?php

    if( isset( $_POST['submit'] ) ){

        $time   = $_POST['time'];
        $flg    = $_POST['flg'];
        $locate = $_POST['locate'];

        $err = '不正な値が入力された可能性があります．投稿に失敗しました．';

        if( $time != '' && $flg != '' && $locate != '' ){

          error_log('time='.$time);

            require_once( 'dbconnect.php' );

            $connect = open_db();
            mysqli_query( $connect, 'SET NAMES utf8' );
            mysqli_set_charset( $connect, 'utf8' );

            mysqli_select_db( $connect, '' );

            $res = mysqli_query( $connect, 'insert into info ( time, locate, flg ) values ('. $time .', "'. $locate .'", '. $flg .');' );

            if( $res ) header( 'Location: index.php' );
            else echo $err;

            mysqli_close($connect);

        } echo $err;

    }

?>
<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="utf-8">
        <title>Watermap Post</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
        <meta http-equiv="content-style-type" content="text/css">
        <meta http-equiv="content-script-type" content="text/javascript">
        <link rel="stylesheet" href="base.css">
        <style>
            #post {
                text-align: center;
                height: 160px;
            }
            input[type=submit]{
                width: 100%;
            }
        </style>
    </head>

    <body>

        <form action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST" id="post">
            <input type="hidden" id="time" name="time" value="">
            <br>
            <select name="flg" id="flg" onchange="updateValue()">
                <option value="0" selected>水が出ない</option>
                <option value="1">水が出る</option>
                <option value="2">水の提供ができる</option>
            </select>
            <br><br>
            <div id='now'>
                <a href="javascript:void(0)" onclick="now()">現在位置を設定</a>
                <br><br>
                <span style="font-size:10px">位置情報の設定できない場合，本体の設定から位置情報の利用を許可してください．</span>
            </div>
            <input type="hidden" name="locate" id="locate" value="">
            <br>
            <input type="submit" name="submit" value="投稿">
        </form>

        <div id="map"></div>

        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script>

            var map,
                markers = ['no', 'ok', 'go'],
                marker  = 0 // Selected marker

            if( !navigator.geolocation )
                document.getElementById( 'now' ).style.display = 'none'

            var m = document.getElementById('map')
            m.style.width  = window.innerWidth + 'px'
            m.style.height = window.innerHeight - 160 + 'px'

            map = new google.maps.Map( m, {
                center: new google.maps.LatLng( 32.7858659,130.7633434 ),
                zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            } )

            var elem = document.getElementById( 'time' ),
                n    = new Date()

            // Create time now
            var month   = n.getMonth() + 1,
                hours   = n.getHours(),
                minutes = n.getMinutes()

            month   = month.toString().length > 1 ? month : '0' + month
            hours   = hours.toString().length > 1 ? hours : '0' + hours
            minutes = minutes.toString().length > 1 ? minutes : '0' + minutes

            elem.value = ''+ Math.round(Date.now()/1000);//'16' + month + hours + minutes
            console.log(elem.value);

            var nowPosition
            map.addListener( 'click', function( e ){
                var latlng = e.latLng
                // Get status
                marker = Number( document.getElementById( 'flg' ).value )
                // console.log( 'set position : ', e )
                if( nowPosition ) nowPosition.setMap( null )
                document.getElementById( 'locate' ).value = latlng.lat() + ',' +  latlng.lng()
                nowPosition = new google.maps.Marker( {
                    position: latlng,
                    map: map,
                    icon: markers[marker] + '.png'
                } )
            } )

            function now(){
                navigator.geolocation.getCurrentPosition( function( position ){
                    var data = position.coords

                    var lat = data.latitude,
                        lng = data.longitude

                    document.getElementById( 'locate' ).value = lat + ',' + lng
                    var latlng = new google.maps.LatLng( lat , lng ), flg

                    marker = document.getElementById( 'flg' ).value

                    if( nowPosition ) nowPosition.setMap( null )
                    nowPosition = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        icon: markers[marker] + '.png'
                    })

                    alert('間違いがなければ "投稿" ボタンをクリックしてください．')
                },
                function( error ){
                    var err_msg
                    switch( error.code ){
                        case 1:
                            errMsg = "位置情報の利用が許可されていません．設定から位置情報の使用を許可してください．"
                            break
                        case 2:
                            errMsg = "デバイスの位置が判定できません．"
                            break
                        case 3:
                            errMsg = "タイムアウトしました．"
                            break
                        }
                        alert( "位置情報の取得に失敗しました．" + errMsg )
                    }
                )
            }

            function updateValue(){
                if( !nowPosition ) return

                var n1 = nowPosition.position.lat(),
                    n2 = nowPosition.position.lng()

                marker = Number( document.getElementById( 'flg' ).value )

                nowPosition.setMap( null )

                nowPosition = new google.maps.Marker( {
                    position: new google.maps.LatLng( n1, n2 ),
                    map: map,
                    icon: markers[marker] + '.png'
                } )

                return true

            }

        </script>

    </body>

</html>
