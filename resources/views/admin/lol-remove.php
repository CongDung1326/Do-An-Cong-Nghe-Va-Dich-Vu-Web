<?php
if (!session_get("information") || session_get("information")['role'] != 2) {
    redirect(base_url(""));
}
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}

$id = check_string(hash_decode(input_get("id")));

$account = get_api(base_url("api/account-lol/GetAccountLOLByIdAccount.php?id=$id"));

if (isset($account->account)) {
    $old_images = list_separator($account->account->image);
    $target_dir_remove = __DIR__ . "/../../../";

    remove_upload_images($target_dir_remove, $old_images);
}


post_api(base_url("api/account/RemoveAccountLOL.php"), ["id" => $id]);

redirect(base_url_admin("manage-item-lol"));
