<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$notification = new Notification();

$data = json_decode(file_get_contents('php://input'));
$id_notification = isset($data->id_notification) ? $data->id_notification : "";

if ($respon === 200) {
    print_r($notification->RemoveNotification($id_notification));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
