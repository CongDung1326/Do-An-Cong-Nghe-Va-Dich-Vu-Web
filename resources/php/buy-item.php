<?php
include_once __DIR__ . "/../../config.php";

if (input_post("id") && input_post("amount")) {
    $id = check_string(hash_decode(input_post("id")));
    $amount = check_string(input_post("amount"));
    $table = "store_account_children";
    $tableUser = "user";
    $tableNotification = "notification_buy";
    $tableAccount = "account";
    $idUser = session_get("information") ? session_get("information")['id'] : false;
    $random = random_string();

    if (!$idUser) {
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

    $query = $call_db->get_row("SELECT store, sold, price FROM $table WHERE id=$id");
    $queryUser = $call_db->get_row("SELECT money FROM $tableUser WHERE id=$idUser");
    $store = $query['store'];
    $sold = $query['sold'];
    $price = $query['price'];
    $money = $queryUser['money'];

    $resultStore = $store - $amount;
    $resultAmount = $sold + $amount;
    $resultPrice = $amount * $price;
    $resultMoney = $money - $resultPrice;

    if ($resultStore < 0) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Số lượng hàng đang có không đủ!"
        ]));
    }
    if ($money < $resultPrice) {
        die(json_encode_utf8([
            "status" => "error",
            "message" => "Tiền không đủ vui lòng nạp thêm!"
        ]));
    }

    $call_db->update($table, [
        "store" => $resultStore,
        "sold" => $resultAmount
    ], "id=$id");
    $call_db->update($tableUser, [
        "money" => $resultMoney
    ], "id=$idUser");
    $call_db->insert($tableNotification, [
        "amount" => $amount,
        "user_id" => $idUser,
        "store_account_children_id" => $id,
        "money" => $resultPrice,
        "unique_code" => $random,
        "time" => time()
    ]);
    $call_db->update($tableAccount, [
        "is_sold" => "T",
        "user_id" => $idUser,
        "unique_code" => $random
    ], "store_account_children_id = $id AND is_sold='F' LIMIT $amount");

    die(json_encode_utf8([
        "status" => "success",
        "message" => "Mua thành công!"
    ]));
}
