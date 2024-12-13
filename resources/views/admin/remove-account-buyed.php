<?php
if (!session_get("information") || session_get("information")['role'] != 2) {
    redirect(base_url());
}
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));

post_api(base_url("api/account/RemoveAccountBuyed.php"), api_verify(["id_account" => $id]));
redirect(base_url_admin("manage-account-buyed"));
