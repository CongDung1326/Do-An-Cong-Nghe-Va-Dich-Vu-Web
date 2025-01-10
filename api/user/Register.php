<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$password_verify = isset($data->password_verify) ? $data->password_verify : "";
$name = isset($data->name) ? $data->name : "";
$email = isset($data->email) ? $data->email : "";

$result = $user->Register($username, $password, $password_verify, $name, $email);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Đăng ký thành công", null, null)));
