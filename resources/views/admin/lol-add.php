<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Thêm Hàng",
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
    "category-add-form.css",
];

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/lol-add-form.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>