<?php
session_start();
include_once "library/session.php";
include_once "library/hash.php";
include_once "library/db.php";
include_once "library/helper.php";

$call_db = new DB();