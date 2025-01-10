<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id) ? $data->id : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $user->RemoveUser($id_user);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Xoá người dùng thành công", null, null)));
