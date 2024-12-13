<?php
include_once __DIR__ . "/../post.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";
$card_type = isset($data->card_type) ? $data->card_type : "";
$money_type = isset($data->money_type) ? $data->money_type : "";
$serial = isset($data->serial) ? $data->serial : "";
$pin = isset($data->pin) ? $data->pin : "";

print_r($bank->AddDeposit($id_user, $card_type, $money_type, $serial, $pin, $data));
