<?php
include_once __DIR__ . "/../get.php";

$account = new Account();
$search = isset($_GET['search']) ? input_get("search") : "";
$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;

print_r($account->GetAllAccountRandom($search, $limit_start, $limit));
