<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));
$table_account = "account";
$table_account_lol = "account_lol";
$call_db->remove($table_account_lol, "account_id=$id");
$call_db->remove($table_account, "id=$id");

redirect(base_url_admin("manage-item-lol"));
