<?php
include_once __DIR__ . "/../get.php";
$respon = include_once __DIR__ . "/../authorization.php";

$notification = new Notification();
$limit = isset($_GET['limit']) ? input_get("limit") : 0;
$limit_start = isset($_GET['limit_start']) ? input_get("limit_start") : 0;
$search = isset($_GET['search']) ? input_get("search") : "";

if ($respon !== 200) {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
    return;
}

$result = $notification->GetAllNotification($limit_start, $limit, $search);
$err_code = $result['err_code'];

if ($err_code != 0) {
    print_r(json_encode_utf8(check_num_error($err_code, "", "notifications", [])));
    return;
};

$notifications = $result['data'];
print_r(json_encode_utf8(check_num_error($err_code, "Lấy dữ liệu thành công", "notifications", $notifications)));
