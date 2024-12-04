<?php
// if (!defined(IN_SITE)) die("The Request Not Found");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $body['title']; ?></title>
    <meta name="description" content="<?= $body['desc']; ?>">
    <meta name="keywords" content="<?= isset($body['keyword']) ? $body['keyword'] : $CMSNT->site('keyword'); ?>">
    <meta name="author" content="<?= $body['author']; ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url("public/css/common/notification.css") ?>">
    <script src="<?= base_url("public/alert-notification/alert-notification.js") ?>" defer async></script>
    <?= loadFileCss("public/css/admin/", $css); ?>
    <?= $body['header'] ?>
</head>

<body>

    <?php
    if (
        !is_page("login") &&
        !is_page("register")
    )
        require_once __DIR__ . "/sidebar.php";
    ?>
    <div class="container">
        <header>
            <?php
            if (!is_admin()) {
                redirect(base_url());
            }
            ?>
            <?php
            if (
                !is_page("login") &&
                !is_page("register")
            )
                require_once __DIR__ . "/nav.php";
            ?>
        </header>