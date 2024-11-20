<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = hash_decode(input_get("id"));
$table = "account";
$call_db->remove($table, "id=$id");

redirect(base_url_admin("manage-item"));
