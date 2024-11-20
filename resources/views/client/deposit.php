<?php
// if (!defined(IN_SITE)) die("The Request Not Found");
$body = [
    "title" => "Nạp Thẻ",
    "desc" => $call_db->site("description"),
    "keyword" => $call_db->site("keyword"),
    "author" => $call_db->site("author")
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