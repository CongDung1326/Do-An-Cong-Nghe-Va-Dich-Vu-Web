<?php
include_once __DIR__ . "/../get.php";
$result_authorization = include_once __DIR__ . "/../authorization.php";
if ($result_authorization != "OK") {
    print_r($result_authorization);
    return;
}


$settings = new Settings();
$key_code = isset($_GET['keyCode']) ? $_GET['keyCode'] : null;

$result = $settings->site($key_code);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print(json_encode_utf8(check_error($err_code, null)));
    $settings->dis_connect();
    return;
}

$data = $result['data'];
print_r(json_encode_utf8(check_error($err_code, [
    "settings" => $data
])));
$settings->dis_connect();
