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
    <meta name="keywords" content="<?= isset($body['keyword']) ? $body['keyword'] : site_v2('SETTING_KEYWORD'); ?>">
    <meta name="author" content="<?= $body['author']; ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/bootstrap.min.css") ?>">
    <!-- Fonts -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/fonts/line-icons.css") ?>">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="<?= base_url("assets/plugins/morris/morris.css") ?>">
    <!-- Responsive Style -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/responsive.css") ?>">

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= base_url() ?>assets/js/jquery-min.js"></script>
    <script src="<?= base_url() ?>assets/js/popper.min.js" defer></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js" defer></script>
    <script src="<?= base_url() ?>assets/js/jquery.app.js" defer></script>
    <script src="<?= base_url() ?>assets/js/main.js" defer></script>

    <!-- Datatable -->
    <script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js" defer></script>
    <script src="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.js" defer></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.css">

    <!--Morris Chart-->
    <script src="<?= base_url() ?>assets/plugins/morris/morris.min.js" defer></script>
    <script src="<?= base_url() ?>assets/plugins/raphael/raphael-min.js" defer></script>
    <script src="<?= base_url() ?>assets/js/dashborad1.js" defer></script>

    <!-- Alert Notification -->
    <script src="<?= base_url() ?>assets/js/alert-notification.js" defer></script>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/alert-notification.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- My CSS -->
    <?= loadFileCss("public/css/client/", $css); ?>

    <?= $body['header'] ?>
</head>

<body>
    <div class="container">
        <div class="header">
            <?php require_once __DIR__ . "/menu.php"; ?>
        </div>