<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => site_v2("SETTING_TITLE"),
    "desc" => site_v2("SETTING_DESCRIPTION"),
    "keyword" => site_v2("SETTING_KEYWORD"),
    "author" => site_v2("SETTING_AUTHOR")
];
$body['header'] = '';
$body['footer'] = '';

$css = [
    "main.css",
];

require_once __DIR__ . "/header.php";
require_once __DIR__ . "/register-form.php";
require_once __DIR__ . "/footer.php";
