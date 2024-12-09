<?php
// if (!defined(IN_SITE)) die("The Request Not Found");
$body = [
    "title" => "Nạp Thẻ",
    "desc" => site("description"),
    "keyword" => site("keyword"),
    "author" => site("author")
];

$body['header'] = '';
$body['footer'] = '';

$css = [
    "deposit.css",
    "deposit-history.css",
    "nav.css",
    "index.css",
    "settings.css",
    "sidebar.css"
];

if (!session_get("information")) {
    redirect(base_url("client/login"));
}

require_once __DIR__ . "/header.php";
?>

<main class="deposit">
    <?php require_once __DIR__ . "/deposit-banner.php"; ?>
    <?php require_once __DIR__ . "/deposit-history.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>