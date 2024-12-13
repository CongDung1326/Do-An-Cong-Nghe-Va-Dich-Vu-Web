<?php
include_once __DIR__ . "/../get.php";

$account = new Account();
$id_account = isset($_GET['id']) ? input_get("id") : "";
$is_sold = isset($_GET['is_sold']) ? input_get("is_sold") : "F";

print_r($account->GetAccountRandomById($id_account, $is_sold));
