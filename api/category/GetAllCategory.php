<?php
include_once __DIR__ . "/../get.php";

$category = new Category();

print_r($category->GetAllCategory());
