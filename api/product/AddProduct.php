<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$title = isset($data->title) ? $data->title : "";
$comment = isset($data->comment) ? $data->comment : "";
$price = isset($data->price) ? $data->price : "";
$id_category = isset($data->id_category) ? $data->id_category : "";

if ($respon === 200) {
    print_r($product->AddProduct($title, $comment, $price, $id_category));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
