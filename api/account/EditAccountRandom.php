<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$id_product = isset($data->id_product) ? $data->id_product : "";
$id_account = isset($data->id_account) ? $data->id_account : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $account->EditAccountRandom($username, $password, $id_account, $id_product);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Sửa thành công", null, null)));
