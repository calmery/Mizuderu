<?php

    if (isset($_POST["submit"])) {
        
        $time = $_POST['time'];
        $flg = $_POST['flg'];
        $locate = $_POST['locate'];

        if( $time != '' && $flg != '' && $locate != '' ){
        
            $connect = mysql_connect('','','');
            mysql_query("SET NAMES utf8",$connect);
            mysql_set_charset("utf8", $connect);

            mysql_select_db('');

            $res = mysql_query('insert into info ( time, locate, flg ) values ('. $time .', "'. $locate .'", '. $flg .');');
            if( $res ) header("Location: index.php");
            else echo "不正な値が入力された可能性があります．";

            mysql_close($connect);
            
        }else echo "不正な値が入力された可能性があります．投稿に失敗しました．";
    }

?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">

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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
    window.onload = function(){
        
        if( !navigator.geolocation ){
            document.getElementById('now').style.display = 'none'
        }
        
        var m = document.getElementById('map')
        m.style.width = window.innerWidth + 'px'
        m.style.height = window.innerHeight - 160 + 'px'
        
        map = new google.maps.Map(m, {
            center: new google.maps.LatLng(32.7858659,130.7633434),
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        })
        
        var elem = document.getElementById( 'time' ), n
        elem.value = ((n = new Date()), '16' + ((n.getMonth()+1).toString().length>1?(n.getMonth()+1):'0'+(n.getMonth()+1)) + (n.getHours().toString().length>1?n.getHours():'0'+n.getHours())+(n.getMinutes().toString().length>1?n.getMinutes():'0'+n.getMinutes()))
        
    }
    
    function now(){
        navigator.geolocation.getCurrentPosition(
            function( position ){
                var data = position.coords

                var lat = data.latitude
                var lng = data.longitude
                
                document.getElementById('locate').value = lat + ',' + lng
                var latlng = new google.maps.LatLng( lat , lng ), flg
                
                new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: (((flg=document.getElementById('flg').value=="1")?"ok":"no") + ".png")
                })
                
                console.log( flg )
                
                if( flg ) document.getElementById('flg').value = 1
                else document.getElementById('flg').value = 0
                
                alert('間違いがなければ "投稿" ボタンをクリックしてください．')
            },

            // [第2引数] 取得に失敗した場合の関数
            function( error ){
                var err_msg
                switch(error.code)
                {
                    case 1:
                        err_msg = "位置情報の利用が許可されていません．設定から位置情報の使用を許可してください．";
                        break;
                    case 2:
                        err_msg = "デバイスの位置が判定できません．";
                        break;
                    case 3:
                        err_msg = "タイムアウトしました．";
                        break;
                }
                alert( "位置情報の取得に失敗しました．" + err_msg )
            }
        )
    }
</script>

<form action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST" id="post">
    <input type="hidden" id="time" name="time" value=""><br>
    <a href="javascript:void(0)" id='now' onclick="now()">現在位置を設定</a><br><br>
    <span style="font-size:10px;">位置情報の設定できない場合，本体の設定から位置情報の利用を許可してください．</span>
    <br><br>
    <select name="flg" id="flg">
        <option value="1">出る</option>
        <option value="0">出ない</option>
    </select>
    <input type="hidden" name="locate" id="locate" value="">
    <br><br>
    <input type="submit" name="submit" value="投稿">
</form>

<div id="map"></div>