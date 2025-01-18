<?php
include_once __DIR__ . "/../get.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account_lol = new AccountLol();
$id_account = isset($_GET['id']) ? input_get("id") : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $account_lol->GetAccountLolByIdAccount($id_account);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "account", [])));
    return;
};

$account_lol = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Lấy dữ liệu thành công", "account", $account_lol)));
