<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($_GET['id_user']) ? input_get("id_user") : "";

print_r($user->GetUserById($id_user, $data));
