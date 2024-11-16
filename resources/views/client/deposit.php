<?php
// if (!defined(IN_SITE)) die("The Request Not Found");
$body = [
    "title" => "Nạp Thẻ",
    "desc" => $call_db->site("description"),
    "keyword" => $call_db->site("keyword"),
    "author" => $call_db->site("author")
];

$body['header'] = '';
$body['footer'] = '';

$css = [
    "register.css",
    "nav.css",
    "index.css",
    "settings.css",
    "sidebar.css"
];

require_once __DIR__ . "/header.php";
?>

<main></main>

<?php
require_once __DIR__ . "/footer.php";
?>