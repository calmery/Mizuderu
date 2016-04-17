<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Watermap Post</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/post.css">
</head>

<body>

    <form action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST" id="post" onsubmit="return confirm('送信してもいいですか？');">

        <input type="hidden" id="time" name="time" value="">
        <input type="hidden" name="locate" id="locate" value="">

        <div id='now' class="box">
            <div class="title">1 位置</div>
            <a href="javascript:void(0)" onclick="now()">現在位置を設定</a>
            <br>
            <div class="memo">マップをタッチすることでの設定も可能です．</div>
        </div>

        <div class="box">
            <div class="title">2 状態</div>
            <select name="flg" id="flg" onchange="updateValue()">
                <option value="0" selected>水が出ない</option>
                <option value="1">水が出る</option>
                <option value="2">水の提供ができる</option>
            </select>
        </div>

        <!--
<div class="box">
<span class="memo">一言コメントを添付できます．</span><br>
<input type="text" id="comment" name="comment" value="">
</div>
-->
        <div class="box">
            <input type="submit" name="submit" value="投稿">
        </div>
        <div class="box">
            <span class="memo">sojo univ. patchworks</span>
        </div>
    </form>

    <div id="map"></div>
    <div id="customZoomBtn">
        <div id="small" class="float_l btn">ズームアウト</div>
        <div id="big" class="float_l btn">ズームイン</div>
    </div>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="js/post.js"></script>

</body>

</html>