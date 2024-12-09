<?php
include_once __DIR__ . "/../get.php";

$account = new Account();
$id_account = isset($_GET['id']) ? input_get("id") : "";

print_r($account->GetAccountRandomById($id_account));
