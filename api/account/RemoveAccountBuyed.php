<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account = new Account();

$data = json_decode(file_get_contents('php://input'));
$id_account = isset($data->id_account) ? $data->id_account : "";

if ($respon === 200) {
    print_r($account->RemoveAccountBuyed($id_account));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
