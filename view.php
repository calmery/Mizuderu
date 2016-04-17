<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Watermap</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <link rel="stylesheet" href="base.css">
    <style>
        #postBtn {
            width: 100%;
            height: 45px;
            background: #fff;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            line-height: 34pt;
            color: #000;
        }

        .question {
            width: 100%;
            height: 45px;
            background: #fff;
            text-align: center;
            font-size: 16px;
            line-height: 34pt;
            color: #000;
        }
    </style>
</head>

<body>
<div class="question">
    水道から水が出ていますか？
</div>
<a href="post.php">
    <div id="postBtn">投稿する</div>
</a>

<!-- View map -->
<div id="map"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>

    var map,
    // index 3 (marker 3) not exist
        markers = ['no', 'ok', 'go', 'go']

    var m = document.getElementById('map')
    m.style.width = window.innerWidth + 'px'
    m.style.height = window.innerHeight - 45 + 'px'

    map = new google.maps.Map(m, {
        center: new google.maps.LatLng(32.7858659, 130.7633434),
        zoom: 9,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    })

    // Set Data
    var position = <?php echo $json; ?>

        console.log(position) // => [{Data},{Data}...,{}]

    var data
    for (var i = 0; i < position.length - 1; i++) {
        data = position[i]['locate'].split(/,/)
        console.log(position[i].flg)
        new google.maps.Marker({
            position: new google.maps.LatLng(data[0], data[1]),
            map: map,
            icon: markers[position[i].flg] + '.png'
        })
    }

</script>

</body>

</html>
