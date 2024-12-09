<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Quản Lý Nạp Thẻ",
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
    "list-item.css",
];

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/list-item.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>