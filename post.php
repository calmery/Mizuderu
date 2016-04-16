<?php

    if (isset($_POST["submit"])) {
        
        $time = $_POST['time'];
        $flg = $_POST['flg'];
        $locate = $_POST['locate'];

        $connect = mysql_connect('localhost','root','pass');
        mysql_query("SET NAMES utf8",$connect);
        mysql_set_charset("utf8", $connect);

        mysql_select_db('water');

        $res = mysql_query('insert into info ( time, locate, flg ) values ('. $time .', "'. $locate .'", '. $flg .');');
        if( $res ) header("Location: index.php");
        else echo "不正な値が入力された可能性があります．";
        
    }

?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<style>
    body{
        padding: 0;
        margin: 0
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
        m.style.height = window.innerHeight + 'px'
        
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
                    icon: (((flg=window.confirm('水は出ますか？'))?"ok":"no") + ".png")
                })
                
                console.log( flg )
                
                if( flg ) document.getElementById('flg').value = 1
                else document.getElementById('flg').value = 0
                
                alert('投稿ありがとうございます．間違いがなければ "投稿" ボタンをクリックしてください．')
            },

            // [第2引数] 取得に失敗した場合の関数
            function( error ){
                alert( '位置情報の取得に失敗しました．' )
            }
        )
    }
</script>

<form action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">
    <label>場所</label>
    <input type="hidden" id="time" name="time" value="">
    <input type="hidden" id="flg" name="flg" value="">
    <input type="text" name="locate" id="locate" value=""><a href="javascript:void(0)" id='now' onclick="now()">現在位置を設定</a>
    <input type="submit" name="submit" value="投稿">
</form>

<div id="map"></div>