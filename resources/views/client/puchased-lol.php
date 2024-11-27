<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Tài Khoản Đã Mua",
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
    "history-puchased.css"
];

if (!session_get("information")) {
    redirect(base_url("client/login"));
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/history-puchased-lol.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>