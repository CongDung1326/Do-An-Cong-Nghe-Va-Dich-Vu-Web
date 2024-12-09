<?php
// if (!defined(IN_SITE)) die("The Request Not Found");

$body = [
    "title" => "Sửa Hàng",
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
    "category-add-form.css",
];

if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/lol-edit-form.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>