<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">

<link rel="stylesheet" href="base.css">
<style>
    #post {
        width: 100%;
        height: 45px;
        background: #fff;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        line-height: 34pt;
        color: #000;
    }
</style>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<a href="post.php">
    <div id="post">投稿する</div>
</a>

<div id="map"></div>
<script type="text/javascript">
    
    var m = document.getElementById('map')
    m.style.width = window.innerWidth + 'px'
    m.style.height = window.innerHeight - 45 + 'px'
    
    map = new google.maps.Map(m, { // #sampleに地図を埋め込む
        center: new google.maps.LatLng(32.7858659,130.7633434), // 地図の中心を指定
        zoom: 9, // 地図のズームを指定
        mapTypeId: google.maps.MapTypeId.ROADMAP
    })
    
    var position = <?php

        date_default_timezone_set('asia/tokyo');
        $connect = mysql_connect('','','');
        mysql_query("SET NAMES utf8",$connect);
        mysql_set_charset("utf8", $connect);

        mysql_select_db('');

        $res = mysql_query('select * from info where time>16'. (date('m')-1). date('d') .'00');
        $json = '[';
        while( $usr = mysql_fetch_array($res) ){
            $json = $json. '{locate:"'. $usr['locate']. '",time:'. $usr['time'] .',flg:'. $usr['flg']. '},';
        }
        $json = $json. '{}]';

        echo $json;

        mysql_close($connect);
        
    ?>

    console.log( position )
        
    var arr
    for( var i=0; i<position.length-1; i++ ){
        arr = position[i]['locate'].split(',')
        new google.maps.Marker({
            position: new google.maps.LatLng(arr[0], arr[1]),
            map: map,
            icon: (position[i].flg ? "ok" : "no" + ".png")
        })
    }

</script>