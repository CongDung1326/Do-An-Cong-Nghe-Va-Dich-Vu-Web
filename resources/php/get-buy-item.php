<?php
include_once __DIR__ . "/../../config.php";

if (input_get("id")) {
    $id = check_string(hash_decode(input_get("id")));
    $output = [];

    if (!is_numeric($id)) {
        return;
    }
    $data = post_api(base_url("api/product/GetProductById.php"), api_verify(["id_product" => $id]))['product'];

    $output = [
        "title" => $data->title,
        "store" => $data->store,
        "price" => $data->price,
    ];

    echo json_encode_utf8($output);
}
