<?php
include_once __DIR__ . "/../get.php";

$category = new Category();
$data = json_decode(file_get_contents('php://input'));

$id_category = isset($data->id_category) ? $data->id_category : "";

$result = $category->GetCategoryById($id_category);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "", "")));
    return;
}

$category = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Lấy dữ liệu thành công", "category", $category)));
