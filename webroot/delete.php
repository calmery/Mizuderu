<?php
require_once("../bootstrap.php");

$post_time = $_POST['post_time'];

$now = time();


if($now < ($post_time + DELETE_LIMIT)){
    if (isset($_POST['del_flg'])) {
        //  当該情報の削除
        $sql = "DELETE FROM info WHERE time = ?";
        $params = [
            $post_time
        ];

        DB::conn()->query($sql, $params);
        header('Location: index.php');

    } else {
        //  削除しようとしているデータが存在しているかをチェック
        $sql = 'SELECT * FROM info WHERE time = ?';
        $params = [
            $post_time
        ];
        $rows = DB::conn()->rows($sql, $params);
    }
    $json = json_safe_encode($rows);
    $json_size = strlen($json);
}

if (isset($json_size) && $json_size > 2) {
    include VIEW_DIR . '/delete.php';
} else {
    echo "この情報は削除されました、または不正なリクエストです";
}