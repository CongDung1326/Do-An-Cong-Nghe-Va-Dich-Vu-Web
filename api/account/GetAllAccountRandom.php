<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$account = new Account();

$data = json_decode(file_get_contents('php://input'));
$id_user = isset($data->id_user) ? $data->id_user : "";
$id_notification = isset($data->id_notification) ? $data->id_notification : "";

$search = isset($_GET['search']) ? input_get("search") : "";
$is_sold = isset($_GET['is_sold']) ? input_get("is_sold") : "ALL";
$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;

if ($respon === 200) {
    print_r($account->GetAllAccountRandom($search, $limit_start, $limit, $id_user, $is_sold, $id_notification));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
