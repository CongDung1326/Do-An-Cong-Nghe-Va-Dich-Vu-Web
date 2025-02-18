<?php
$json_errors = file_get_contents(__DIR__ . "/../json/errors.json");
$data_errors = json_decode_utf8_v2($json_errors);
