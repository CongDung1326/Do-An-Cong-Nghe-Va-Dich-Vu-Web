<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$id_bank = isset($data->id_bank) ? $data->id_bank : "";
$status = isset($data->status) ? $data->status : "";

if ($respon === 200) {
    print_r($bank->Deposit($id_user, $id_bank, $status));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
