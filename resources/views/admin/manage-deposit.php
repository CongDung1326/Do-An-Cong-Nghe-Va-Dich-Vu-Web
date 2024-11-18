<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Quản Lý Nạp Thẻ",
    "desc" => $call_db->site("description"),
    "keyword" => $call_db->site("keyword"),
    "author" => $call_db->site("author")
];

$body['header'] = '';
$body['footer'] = '';

$css = [
    "index.css",
    "settings.css",
    "footer.css",
    "header.css",
    "nav.css",
    "index.css",
    "sidebar.css",
    "list-deposit.css",
];

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/list-deposit.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>