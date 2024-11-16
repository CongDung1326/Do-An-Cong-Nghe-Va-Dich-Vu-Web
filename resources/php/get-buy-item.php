<?php
include_once __DIR__ . "/../../config.php";

if (input_get("id")) {
    $id = hash_decode(input_get("id"));
    $output = [];

    if (!is_numeric($id)) {
        return;
    }
    $query = "SELECT * FROM store_account_children WHERE id=$id";
    $data = $call_db->get_row($query);

    $output = [
        "title" => $data['title'],
        "store" => $data['store'],
        "price" => $data['price'],
    ];

    echo json_encode_utf8($output);
}
