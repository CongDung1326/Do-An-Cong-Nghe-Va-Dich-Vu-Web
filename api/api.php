<?php
include_once __DIR__ . "/../config.php";

function get_api($url)
{
    $ch = curl_init();
    $result = [];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, API_USERNAME . ":" . API_PASSWORD);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $resp = json_decode_utf8($resp);

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
    curl_setopt($ch, CURLOPT_USERPWD, API_USERNAME . ":" . API_PASSWORD);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode_utf8($data));

    $resp = curl_exec($ch);
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $resp = json_decode_utf8($resp);

        $result = $resp;
    }

    curl_close($ch);
    return $result;
}
