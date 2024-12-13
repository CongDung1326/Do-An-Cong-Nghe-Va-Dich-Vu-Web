<?php
include_once __DIR__ . "/../post.php";

$notification = new Notification();

$data = json_decode(file_get_contents('php://input'));
$id_notification = isset($data->id_notification) ? $data->id_notification : "";

print_r($notification->RemoveNotification($id_notification, $data));
