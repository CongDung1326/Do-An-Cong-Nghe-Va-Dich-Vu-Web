<?php
include_once __DIR__ . "/../post.php";

$product = new Product();
$data = json_decode(file_get_contents('php://input'));

$id_product = isset($data->id_product) ? $data->id_product : "";

print_r($product->GetProductById($id_product, $data));
