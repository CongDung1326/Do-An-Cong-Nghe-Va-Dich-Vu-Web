<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$user = new User();

$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$username = isset($data->username_user) ? $data->username_user : "";
$password = isset($data->password_user) ? $data->password_user : "";
$name = isset($data->name) ? $data->name : "";
$age = isset($data->age) ? $data->age : "";
$email = isset($data->email) ? $data->email : "";
$number_phone = isset($data->number_phone) ? $data->number_phone : "";
$avatar = isset($data->avatar) ? $data->avatar : "";
$money = isset($data->money) ? $data->money : "";
$role_id = isset($data->role_id) ? $data->role_id : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $user->EditUser($id_user, $username, $password, $name, $age, $email, $number_phone, $avatar, $money, $role_id);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Sửa thành công", null, null)));
