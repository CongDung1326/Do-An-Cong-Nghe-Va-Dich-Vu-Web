<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Mua Acc Liên Minh",
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
    "form-buy-lol.css",
];

if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/form-buy-lol.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>