<?php
include_once __DIR__ . "/../post.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$id_bank = isset($data->id_bank) ? $data->id_bank : "";
$status = isset($data->status) ? $data->status : "";

print_r($bank->Deposit($id_user, $id_bank, $status, $data));
