<?php
require_once("../bootstrap.php");

$now = time();
$from_time = $now - (60 * 60 * 24 * 2);
$query = 'select * from info where time > ' . $from_time . ' order by time desc';
$arr = DB::conn()->rows($query, [$from_time]);


foreach($arr as $a){

    if(empty($a["address"])){
        $query = array(
            "latlng" => $a["locate"],
            "language" => "ja",
            "sensor" => false
        );
        $res = callApi("GET", "https://maps.googleapis.com/maps/api/geocode/json", $query);

        $address= $res["results"][0]["formatted_address"];

        $query = "UPDATE info SET address = '" . $address . "' WHERE Id = " . $a["Id"] . ";";

        DB::conn()->query($query, [$address]);
    }
}


function callApi($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return json_decode($result, true);
}
