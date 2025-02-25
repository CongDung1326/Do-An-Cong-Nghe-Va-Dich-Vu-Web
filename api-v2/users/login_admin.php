<?php
include_once __DIR__ . "/../post.php";
$result_authorization = include_once __DIR__ . "/../authorization.php";
if ($result_authorization != "OK") {
    print_r($result_authorization);
    return;
}

$users = new Users();
$data = json_decode(file_get_contents('php://input'));

$result = $users->login_admin($data);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print(json_encode_utf8(check_error($err_code, null)));
    $users->dis_connect();
    return;
}

$data = $result['data'];
print_r(json_encode_utf8(check_error($err_code, [
    "user" => $data
])));
$users->dis_connect();
