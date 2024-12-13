<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$id_user = isset($data->id_user) ? $data->id_user : "";

print_r($user->RemoveUser($id_user, $data));
