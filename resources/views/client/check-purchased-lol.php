<?php
$body = [
    "title" => "Kiểm Tra Sản Phẩm",
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
    "account-purchased.css"
];


if (!session_get("information")) {
    redirect(base_url("client/login"));
}
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}
$id_notification = hash_decode(input_get("id"));

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/account-purchased-lol.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>