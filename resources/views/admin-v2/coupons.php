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

// $css = [
//     "index.css",
//     "settings.css",
//     "footer.css",
//     "header.css",
//     "nav.css",
//     "index.css",
//     "banner.css",
//     "sidebar.css",
//     "shop-account.css",
//     "form-buy.css",
// ];

require_once __DIR__ . "/header.php";
require_once __DIR__ . "/coupons-form.php";
require_once __DIR__ . "/footer.php";
