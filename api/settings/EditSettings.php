<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$settings = new Settings();

$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$title = isset($data->title) ? $data->title : "";
$description = isset($data->description) ? $data->description : "";
$keyword = isset($data->keyword) ? $data->keyword : "";
$logo = isset($data->logo) ? $data->logo : "";
$name_shop = isset($data->name_shop) ? $data->name_shop : "";
$discount = isset($data->discount) ? $data->discount : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $settings->EditSettings($id_user, $title, $description, $logo, $keyword, $name_shop, $discount);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Sửa thành công", null, null)));
