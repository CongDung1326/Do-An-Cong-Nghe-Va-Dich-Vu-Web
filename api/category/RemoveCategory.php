<?php
include_once __DIR__ . "/../post.php";

$category = new Category();
$data = json_decode(file_get_contents('php://input'));

$id_category = isset($data->id_category) ? $data->id_category : "";

print_r($category->RemoveCategory($id_category));
