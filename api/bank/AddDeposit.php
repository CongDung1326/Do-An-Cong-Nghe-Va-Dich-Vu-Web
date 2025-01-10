<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$card_type = isset($data->card_type) ? $data->card_type : "";
$money_type = isset($data->money_type) ? $data->money_type : "";
$serial = isset($data->serial) ? $data->serial : "";
$pin = isset($data->pin) ? $data->pin : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $bank->AddDeposit($id_user, $card_type, $money_type, $serial, $pin);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
};

print_r(json_encode_utf8(check_num_error($err_code, "Nạp thẻ thành công", null, null)));
