<?php
include_once __DIR__ . "/../services/api.php";
$api = new Api();
$respon_code = 0;

if ($api->CheckIsAdmin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    http_response_code(200);
    $respon_code = 200;
} else {
    http_response_code(401);
    $respon_code = 401;
}

return $respon_code;
