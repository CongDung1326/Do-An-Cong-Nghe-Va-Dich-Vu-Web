<?php
include_once __DIR__ . "/../../config.php";

if (input_post("id") && input_post("amount")) {
    $id = check_string(hash_decode(input_post("id")));
    $amount = check_string(input_post("amount"));
    $id_user = session_get("information")['id'];

    if (!$id_user) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Có gì đó sai sai!"
        ]));
    }
    if (!is_numeric($id)) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Id có gì đó sai!"
        ]));
    }
    if (!is_numeric($amount)) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Vui lòng nhập phần số lượng!"
        ]));
    }

    $respon = post_api(base_url("api/product/BuyItemProduct.php"), api_verify([
        "id_user" => $id_user,
        "id_product" => $id,
        "amount" => $amount
    ]));

    die(json_encode_utf8($respon));
}
