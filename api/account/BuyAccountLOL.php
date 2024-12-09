<?php
include_once __DIR__ . "/../post.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$id_account = isset($data->id_account) ? $data->id_account : "";
$id_user = isset($data->id_user) ? $data->id_user : "";


print_r($account->BuyAccountLOL($id_account, $id_user));
