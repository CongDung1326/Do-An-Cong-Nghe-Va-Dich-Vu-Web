<?php
include_once __DIR__ . "/../post.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;
$status = isset($_GET['status']) ? input_get("status") : "ALL";

print_r($bank->GetAllBank($limit_start, $limit, $status, $data));
