<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Đơn Hàng Đã Bán",
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
    "order-sold-form.css",
];

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/order-sold-form.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>