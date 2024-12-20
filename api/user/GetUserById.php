<?php
include_once __DIR__ . "/../post.php";
$respon = include_once __DIR__ . "/../authorization.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($_GET['id_user']) ? input_get("id_user") : "";

if ($respon === 200) {
    print_r($user->GetUserById($id_user, $data));
} else {
    print_r(json_encode_utf8([
        "status" => "error",
        "message" => "Bạn không đủ quyền hạn để truy cập"
    ]));
}
