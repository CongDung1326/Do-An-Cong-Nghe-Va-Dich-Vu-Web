<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}
if (!session_get("information")) {
    redirect(base_url("client/login"));
}

$id = check_string(hash_decode(input_get("id")));
$id_user = session_get("information")['id'];
$table = "notification_buy";
$call_db->update($table, ["is_show" => "F"], "id=$id AND user_id=$id_user");

redirect(base_url("client/purchased-lol"));
