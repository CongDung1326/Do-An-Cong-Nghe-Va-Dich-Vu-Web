<?php
include_once __DIR__ . "/../post.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$id_product = isset($data->id_product) ? $data->id_product : "";
$id_account = isset($data->id_account) ? $data->id_account : "";

print_r($account->EditAccountRandom($username, $password, $id_account, $id_product));
