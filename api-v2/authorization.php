<?php
include_once __DIR__ . "/../services-v2/settings.service.php";
$settings = new Settings();

if (
    isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
    && $settings->site("AUTHORIZATION_USERNAME")['data'] == $_SERVER['PHP_AUTH_USER']
    && $settings->site("AUTHORIZATION_PASSWORD")['data'] == $_SERVER['PHP_AUTH_PW']
) {
    http_response_code(200);
    $respon_code = 200;
} else {
    http_response_code(401);
    $respon_code = 401;
}

$num_error_authorization = 5;
if ($respon_code !== 200) return json_encode_utf8(check_error($num_error_authorization, ""));
return "OK";
