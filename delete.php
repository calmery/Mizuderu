<?php
$post_time = $_POST['post_time'];

date_default_timezone_set('asia/tokyo');
require_once('dbconnect.php');

$connect = open_db();

mysqli_query($connect, 'SET NAMES utf8');
mysqli_set_charset($connect, 'utf8');

mysqli_select_db($connect, 'water');

if ( isset( $_POST['del_flg'] ) ) {
    //  当該情報の削除
    $query = 'delete from info where time = '. $post_time;
    $res = mysqli_query($connect, $query);
    error_log($query);
    header( "Location: ./" ) ;
} else {
    //  削除しようとしているデータが存在しているかをチェック
    $query = 'select * from info where time = '. $post_time;
}

error_log($query);
$res = mysqli_query($connect, $query);

function json_safe_encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

$arr = array();
while ($data = mysqli_fetch_array($res)) {
//    $json = $json . json_encode($data);
//    //$json = $json. '{locate:"'. $data['locate']. '",time:'. $data['time'] .',flg:'. $data['flg']. '},';
//    $json = $json . ',';
    $arr[] = $data;
}
$json = json_safe_encode($arr, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

mysqli_close($connect);

$json_size = strlen($json);

if ($json_size > 2) {
    echo "この情報を削除してもよろしいですか？";
    echo "<form name='del' method='POST' action='delete.php'>";
    echo "<input type=hidden name='post_time' value='$post_time'>";
    echo "<input type=hidden name='del_flg' value='yes'>";
    echo "<input type='submit' name='submit' value='削除' />";
} else {
    echo "この情報は削除されました、または不正なリクエストです";
}
?>
