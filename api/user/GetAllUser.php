<?php
include_once __DIR__ . "/../post.php";

$user = new User();

$data = json_decode(file_get_contents('php://input'));

$search = isset($_GET['search']) ? input_get("search") : "";
$is_sold = isset($_GET['is_sold']) ? input_get("is_sold") : "ALL";
$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;

print_r($user->GetAllUser($search, $limit_start, $limit, $data));
