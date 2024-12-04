<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));
$table = "notification_buy";
$table_account = "account";
$unique_code = $call_db->get_row("SELECT unique_code FROM $table WHERE id = $id")['unique_code'];

if (!empty($unique_code)) {
    $call_db->remove($table_account, "unique_code='$unique_code'");
    $call_db->remove($table, "id=$id");
}

redirect(base_url_admin("manage-order-sold"));
