<?php
include_once __DIR__ . "/../post.php";

$user = new User();

$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$title = isset($data->title) ? $data->title : "";
$description = isset($data->description) ? $data->description : "";
$keyword = isset($data->keyword) ? $data->keyword : "";
$logo = isset($data->logo) ? $data->logo : "";
$name_shop = isset($data->name_shop) ? $data->name_shop : "";
$discount = isset($data->discount) ? $data->discount : "";

print_r($user->EditSettings($id_user, $title, $description, $logo, $keyword, $name_shop, $discount, $data));
