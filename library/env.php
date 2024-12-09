<?php
$env = parse_ini_file(__DIR__ . "/../.env");

// Define
define("DB_HOST", $env['DB_HOST']);
define("DB_NAME", $env['DB_NAME']);
define("DB_PASSWORD", $env['DB_PASSWORD']);
define("DB_TABLE", $env['DB_TABLE']);
define("API_USERNAME", $env['API_USERNAME']);
define("API_PASSWORD", $env['API_PASSWORD']);
