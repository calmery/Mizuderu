<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Watermap</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

<div id="tools">
    <div style="margin:15px 0;text-align:center">
        水道から水が出ていますか？
    </div>
    <a href="post.php">
        <div id="postBtn">投稿する</div>
    </a>

    <div class="memo">
        <br>
        <img src="no.png"> 水が出ない&nbsp;
        <img src="ok.png"> 水は出る&nbsp;
        <img src="go.png"> 水の提供可能
    </div>
</div>

<!-- View map -->
<div id="map" data-source='<?php echo $json; ?>'></div>
<div id="customZoomBtn">
    <div id="small" class="float_l btn">ズームアウト</div>
    <div id="big" class="float_l btn">ズームイン</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/index.js"></script>

</body>

</html>
