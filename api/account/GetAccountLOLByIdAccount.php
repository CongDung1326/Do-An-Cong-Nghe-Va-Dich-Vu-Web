<?php
include_once __DIR__ . "/../get.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account = new Account();
$id_account = isset($_GET['id']) ? input_get("id") : "";
$is_sold = isset($_GET['is_sold']) ? input_get("is_sold") : "F";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $account->GetAccountLOLByIdAccount($id_account, $is_sold);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "account", [])));
    return;
};

$accounts = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Lấy dữ liệu thành công", "account", $accounts)));
