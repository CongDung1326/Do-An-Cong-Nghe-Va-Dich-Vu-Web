<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Tài Khoản Đã Mua",
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
    "history-purchased.css"
];

if (!session_get("information")) {
    redirect(base_url("client/login"));
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/history-purchased.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>