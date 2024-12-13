<?php
include_once __DIR__ . "/../post.php";

$category = new Category();
$data = json_decode(file_get_contents('php://input'));

$name = isset($data->name) ? $data->name : "";

print_r($category->AddCategory($name));
