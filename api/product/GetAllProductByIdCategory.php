<?php
include_once __DIR__ . "/../get.php";
$respon = include_once __DIR__ . "/../authorization.php";

$product = new Product();
$id_category = isset($_GET['id_category']) ? input_get("id_category") : "";

if ($respon === 200) {
    print_r($product->GetAllProductByIdCategory($id_category));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
