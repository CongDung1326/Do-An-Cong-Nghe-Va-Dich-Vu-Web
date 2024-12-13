<?php
include_once __DIR__ . "/../get.php";

$notification = new Notification();
$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;
$search = isset($_GET['search']) ? input_get("search") : "";

print_r($notification->GetAllNotification($limit_start, $limit, $search));
