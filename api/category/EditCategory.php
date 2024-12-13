<?php
include_once __DIR__ . "/../get.php";

$category = new Category();
$data = json_decode(file_get_contents('php://input'));

$name = isset($data->name) ? $data->name : "";
$id_category = isset($data->id_category) ? $data->id_category : "";

print_r($category->EditCategory($id_category, $name));
