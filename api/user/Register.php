<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

$username = isset($data->username) ? $data->username : "";
$password = isset($data->password) ? $data->password : "";
$password_verify = isset($data->password_verify) ? $data->password_verify : "";
$name = isset($data->name) ? $data->name : "";
$email = isset($data->email) ? $data->email : "";

print_r($user->Register($username, $password, $password_verify, $name, $email));
