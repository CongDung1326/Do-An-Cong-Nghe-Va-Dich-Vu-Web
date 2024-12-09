<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Xem Ảnh",
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
    "list-image.css",
];

if (!input_get("id")) redirect(base_url_admin());

require_once __DIR__ . "/header.php";
?>

<main>
    <?php require_once __DIR__ . "/list-image.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>