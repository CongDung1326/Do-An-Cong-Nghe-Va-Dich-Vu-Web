<?php
include_once __DIR__ . "/../post.php";

$account = new Account();
$data = json_decode(file_get_contents('php://input'));

$id_account = isset($data->id) ? $data->id : "";

print_r($account->RemoveAccountLOL($id_account));
