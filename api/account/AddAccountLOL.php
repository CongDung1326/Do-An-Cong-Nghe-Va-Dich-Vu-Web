<?php
include_once __DIR__ . "/../post.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$number_char = isset($data->number_char) ? $data->number_char : "";
$number_skin = isset($data->number_skin) ? $data->number_skin : "";
$id_rank = isset($data->id_rank) ? $data->id_rank : "";
$price = isset($data->price) ? $data->price : "";
$images = isset($data->images) ? $data->images : "";

print_r($account->AddAccountLOL($username, $password, $number_char, $number_skin, $id_rank, $price, $images));