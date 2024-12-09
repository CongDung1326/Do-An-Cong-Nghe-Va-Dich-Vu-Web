<?php
include_once __DIR__ . "/../get.php";

$product = new Product();
$id_category = isset($_GET['id_category']) ? input_get("id_category") : "";

print_r($product->GetAllProductByIdCategory($id_category));
