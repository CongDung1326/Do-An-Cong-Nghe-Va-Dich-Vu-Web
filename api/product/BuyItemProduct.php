<?php
include_once __DIR__ . "/../post.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$id_product = isset($data->id_product) ? $data->id_product : "";
$id_user = isset($data->id_user) ? $data->id_user : "";
$amount = isset($data->amount) ? $data->amount : "";

print_r($product->BuyItemProduct($id_product, $id_user, $amount, $data));