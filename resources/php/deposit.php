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

    $call_db->insert("bank", [
        "type" => $card_type,
        "serial" => $serial,
        "amount" => $money_type * discount(site("discount")),
        "pin" => $pin,
        "status" => "W",
        "user_id" => $id,
        "time_created" => time()
    ]);


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
