<?php
include_once __DIR__ . "/../config.php";

function get_api($url)
{
    $ch = curl_init();
    $result = [];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $resp = convert_object_to_array($resp);

        $result = $resp;
    }

    curl_close($ch);
    return $result;
}
function post_api($url, $data)
{
    $ch = curl_init();
    $result = [];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode_utf8($data));

    $resp = curl_exec($ch);
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $resp = convert_object_to_array($resp);

        $result = $resp;
    }

    curl_close($ch);
    return $result;
}
