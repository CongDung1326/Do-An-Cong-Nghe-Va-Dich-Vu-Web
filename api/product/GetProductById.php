<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$id_product = isset($data->id_product) ? $data->id_product : "";

if ($respon === 200) {
    print_r($product->GetProductById($id_product));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
