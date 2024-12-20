<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$bank = new Bank();
$data = json_decode(file_get_contents('php://input'));

$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;
$status = isset($_GET['status']) ? input_get("status") : "ALL";

if ($respon === 200) {
    print_r($bank->GetAllBank($limit_start, $limit, $status));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
