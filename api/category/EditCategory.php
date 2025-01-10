<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$category = new Category();
$data = json_decode(file_get_contents('php://input'));

$name = isset($data->name) ? $data->name : "";
$id_category = isset($data->id_category) ? $data->id_category : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $category->EditCategory($id_category, $name);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

print_r(json_encode_utf8(check_num_error($err_code, "Sửa danh mục thành công", null, null)));
