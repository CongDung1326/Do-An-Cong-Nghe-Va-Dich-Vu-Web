<?php
include_once __DIR__ . "/../post.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$title = isset($data->title) ? $data->title : "";
$comment = isset($data->comment) ? $data->comment : "";
$price = isset($data->price) ? $data->price : "";
$id_category = isset($data->id_category) ? $data->id_category : "";
$id_product = isset($data->id_product) ? $data->id_product : "";

print_r($product->EditProduct($title, $comment, $price, $id_category, $id_product, $data));
