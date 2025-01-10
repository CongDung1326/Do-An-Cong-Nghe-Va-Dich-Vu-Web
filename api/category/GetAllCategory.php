<?php
include_once __DIR__ . "/../get.php";

$category = new Category();

$result = $category->GetAllCategory();
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "categories", [])));
    return;
}

$category = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Lấy dữ liệu thành công", "categories", $category)));
