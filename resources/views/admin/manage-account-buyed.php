<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Tài Khoản Đã Bán",
    "desc" => site("description"),
    "keyword" => site("keyword"),
    "author" => site("author")
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
    "list-account-buyed.css",
];

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/list-account-buyed.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>