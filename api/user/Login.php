<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";

$result = $user->Login($username, $password);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

$user = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Đăng nhập thành công", "user", $user)));
