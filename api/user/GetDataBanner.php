<?php
include_once __DIR__ . "/../post.php";

$user = new User();
$data = json_decode(file_get_contents('php://input'));

print_r($user->GetDataBanner($data));
