<?php
include_once __DIR__ . "/../post.php";

$notification = new Notification();

$data = json_decode(file_get_contents('php://input'));
$id_user = isset($data->id_user) ? $data->id_user : "";

$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;
$is_show = isset($_GET['is_show']) ? input_get("is_show") : "ALL";
$search = isset($_GET['search']) ? input_get("search") : "";

print_r($notification->GetAllNotificationRandom($search, $limit_start, $limit, $id_user, $is_show, $data));
