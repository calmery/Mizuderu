<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<div id="map" style="width: 400px; height: 280px;"></div>
<script type="text/javascript">
    
    map = new google.maps.Map(document.getElementById('map'), { // #sampleに地図を埋め込む
        center: new google.maps.LatLng(32.7858659,130.7633434), // 地図の中心を指定
        zoom: 9, // 地図のズームを指定
        mapTypeId: google.maps.MapTypeId.ROADMAP
    })
    
    var position = <?php


        date_default_timezone_set('asia/tokyo');
        $connect = mysql_connect('localhost','root','pass');
        mysql_query("SET NAMES utf8",$connect);
        mysql_set_charset("utf8", $connect);

        mysql_select_db('water');

        $res = mysql_query('select * from info where time>16'. date('md') .'00');
        $json = '[';
        while( $usr = mysql_fetch_array($res) ){
            $json = $json. '{locate:"'. $usr['locate']. '",time:'. $usr['time'] .',flg:'. $usr['flg']. '},';
        }
        $json = $json. '{}]';

        echo $json;

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
<a href="post.php">投稿する</a>