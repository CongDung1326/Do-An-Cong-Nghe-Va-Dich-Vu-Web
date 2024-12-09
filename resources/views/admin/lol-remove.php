<?php
if (!session_get("information")) {
    redirect(base_url("client/login"));
}
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));
post_api(base_url("api/account/RemoveAccountLOL.php"), ["id" => $id]);

redirect(base_url_admin("manage-item-lol"));
