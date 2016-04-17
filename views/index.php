<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Watermap</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="content-style-type" content="text/css">
    <meta http-equiv="content-script-type" content="text/javascript">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

    <div id="tools">
        <a href="post.php">
            <div id="postBtn">
                <div class="memoMizu">水は出ますか？</div>
                <br>投稿する
            </div>
        </a>
        <div id="colors">
            <div id="center">
                <div class="color">
                    <div class="pallet float_l" style="background: #D0021B"></div>
                    <div class="palletText float_l">水は出ません</div>
                </div>
                <div class="color">
                    <div class="pallet float_l" style="background: #4A90E2"></div>
                    <div class="palletText float_l">水が出ます</div>
                </div>
                <div class="color">
                    <div class="pallet float_l" style="background: #F5A623"></div>
                    <div class="palletText float_l">水の提供が可能</div>
                </div>
            </div>
        </div>
        <a href="javascript:void(0)">
            <div id="range-toggle">日付で絞る</div>
        </a>
        <div id="time-range" style="display:none">
            <input type="text" id="amount" style="background: #4A90E2; border:0;font-weight: bold;color:#fff;" size="100" />
            <input type="hidden" id="start" value="<?php echo $from_time; ?>">
            <input type="hidden" id="end" value="<?php echo $now; ?>">
            <div id="slider-range"></div>
        </div>
    </div>

    <!-- View map -->
    <div id="map" data-source='<?php echo $json; ?>'></div>
    <div id="customZoomBtn">
        <div id="small" class="float_l btn">ズームアウト</div>
        <div id="big" class="float_l btn">ズームイン</div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/timerange.js"></script>
    <script>
        $("#range-toggle").click(function () {
            $("#time-range").toggle("fold", 1000);
        });
    </script>
</body>

</html>