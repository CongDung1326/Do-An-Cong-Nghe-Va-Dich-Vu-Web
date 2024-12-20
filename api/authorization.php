<?php
$respon_code = 0;

if ((isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] == "admin")) and
    (isset($_SERVER['PHP_AUTH_PW']) && ($_SERVER['PHP_AUTH_PW'] == "123"))
) {
    http_response_code(200);
    $respon_code = 200;
} else {
    http_response_code(401);
    $respon_code = 401;
}

return $respon_code;
