<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Quản Lý Người Dùng",
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
    "user-list.css",
];

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/user-list.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>