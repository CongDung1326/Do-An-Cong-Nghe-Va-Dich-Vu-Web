<?php
include_once __DIR__ . "/../post.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$title = isset($data->title) ? $data->title : "";
$comment = isset($data->comment) ? $data->comment : "";
$price = isset($data->price) ? $data->price : "";
$id_category = isset($data->id_category) ? $data->id_category : "";

print_r($product->AddProduct($title, $comment, $price, $id_category, $data));
