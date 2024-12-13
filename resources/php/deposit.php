<?php
include_once __DIR__ . "/../../config.php";

if (
    input_post("card_type")
    && input_post("money_type")
    && input_post("serial")
    && input_post("pin")
    && input_post("id")
) {
    $card_type = check_string(input_post("card_type"));
    $money_type = check_string(input_post("money_type"));
    $serial = check_string(input_post("serial"));
    $pin = check_string(input_post("pin"));
    $id = hash_decode(check_string(input_post("id")));

    if (!$id) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Lỗi id!"
        ]));
    }

    post_api(base_url("api/bank/AddDeposit.php"), api_verify([
        "id_user" => $id,
        "card_type" => $card_type,
        "money_type" => $money_type,
        "serial" => $serial,
        "pin" => $pin
    ]));

    die(json_encode_utf8([
        "status" => "success",
        "message" => "Nạp Thành Công!"
    ]));
} else {
    die(json_encode_utf8([
        "status" => "error",
        "message" => "Vui Lòng Chọn Loại Thẻ Và Mệnh Giá!"
    ]));
}
