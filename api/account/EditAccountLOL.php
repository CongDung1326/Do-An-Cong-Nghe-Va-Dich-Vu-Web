<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$id_account = isset($data->id_account) ? $data->id_account : "";
$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$number_char = isset($data->number_char) ? $data->number_char : "";
$number_skin = isset($data->number_skin) ? $data->number_skin : "";
$id_rank = isset($data->id_rank) ? $data->id_rank : "";
$price = isset($data->price) ? $data->price : "";
$images = isset($data->images) ? $data->images : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $account->EditAccountLOL($id_account, $username, $password, $number_char, $number_skin, $id_rank, $price, $images);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Sửa thành công", null, null)));
