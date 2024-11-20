<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = hash_decode(input_get("id"));
$table = "store_account_children";
$call_db->remove($table, "id=$id");

redirect(base_url_admin("manage-store"));
