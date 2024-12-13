<?php
if (!session_get("information") || session_get("information")['role'] != 2) {
    redirect(base_url(""));
}
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));

$respon = post_api(base_url("api/notification/RemoveNotifiaction.php"), api_verify(["id_notification" => $id]));
if ($respon['status'] == "error") redirect(base_url_admin());

redirect(base_url_admin("manage-order-sold"));
