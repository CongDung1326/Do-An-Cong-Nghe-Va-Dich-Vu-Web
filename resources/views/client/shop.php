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

$css = [
    "index.css",
    "settings.css",
    "footer.css",
    "header.css",
    "nav.css",
    "index.css",
    "banner.css",
    "sidebar.css",
    "list-account.css",
];

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/list-account.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>