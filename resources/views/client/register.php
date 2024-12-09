<?php
// if (!defined(IN_SITE)) die("The Request Not Found");
$body = [
    "title" => "Đăng Ký",
    "desc" => site("description"),
    "keyword" => site("keyword"),
    "author" => site("author")
];

$body['header'] = '';
$body['footer'] = '';

$css = [
    "register.css",
    "nav.css",
    "index.css",
    "settings.css",
    "sidebar.css"
];

require_once __DIR__ . "/header.php";
?>

<main class="register" style="background: url('<?= base_url("assets/storage/bg_loginH0O.png"); ?>');">
    <?php require_once __DIR__ . "/form-register.php"; ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>