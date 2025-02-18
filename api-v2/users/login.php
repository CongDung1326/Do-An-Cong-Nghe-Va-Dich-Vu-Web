<?php
include_once __DIR__ . "/../post.php";

$users = new Users();
$data = json_decode(file_get_contents('php://input'));

$result = $users->login($data);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print(json_encode_utf8(check_error($err_code, null)));
    return;
}

$data = $result['data'];
print_r(json_encode_utf8(check_error($err_code, [
    "user" => $data
])));
