<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => site("title"),
    "desc" => site("description"),
    "keyword" => site("keyword"),
    "author" => site("author")
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
require_once __DIR__ . "/settings-form.php";
require_once __DIR__ . "/footer.php";
