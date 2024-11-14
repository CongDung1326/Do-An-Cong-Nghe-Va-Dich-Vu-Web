<?php
define("IN_SITE", true);
session_start();
include_once "library/db.php";
include_once "library/helper.php";

$call_db = new DB();

// Config is file exist
$module = !empty($_GET['module']) ? check_string($_GET['module']) : "client";
$action =  !empty($_GET['action']) ? check_string($_GET['action']) : "home";

if ($action == "header" || $action == "footer" || $action == "sidebar" || $action == "nav") {
    require_once __DIR__ . "/resources/views/common/404.php";
    exit();
}

$path = "resources/views/$module/$action.php";
if (file_exists($path)) {
    require_once __DIR__ . "/" . $path;
} else {
    require_once __DIR__ . "/resources/views/common/404.php";
    exit();
}
