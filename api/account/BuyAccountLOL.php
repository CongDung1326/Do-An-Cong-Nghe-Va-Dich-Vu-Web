<?php
include_once __DIR__ . "/../post.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$id_account = isset($data->id_account) ? $data->id_account : "";
$id_user = isset($data->id_user) ? $data->id_user : "";

$result = $account->BuyAccountLOL($id_account, $id_user);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Mua thành công", null, null)));
