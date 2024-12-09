<?php
include_once __DIR__ . "/../post.php";

$settings = new Settings();
$data = json_decode(file_get_contents('php://input'));

print_r($settings->GetAllSettings($data));
