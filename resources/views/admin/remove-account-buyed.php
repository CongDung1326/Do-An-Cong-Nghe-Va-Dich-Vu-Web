<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));
$query = "SELECT type FROM account WHERE id = $id";
$check_type = $call_db->get_row($query);

if ($check_type['type'] == "lol") {
    $table_account = "account";
    $table_account_lol = "account_lol";
    $table_notification_buy = "notification_buy";
    $id_lol = $call_db->get_row("SELECT id FROM account_lol WHERE account_id = $id")['id'];

    $call_db->remove($table_notification_buy, "account_lol_id=$id_lol");
    $call_db->remove($table_account_lol, "account_id=$id");
    $call_db->remove($table_account, "id=$id");
} else {
    $table = "account";
    $call_db->remove($table, "id=$id");
}

redirect(base_url_admin("manage-account-buyed"));
