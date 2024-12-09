<?php
if (!input_get("id") || !is_numeric(hash_decode(input_get("id")))) {
    redirect(base_url());
}
if (!session_get("information")) {
    redirect(base_url("client/login"));
}

$id = check_string(hash_decode(input_get("id")));
$id_user = session_get("information")['id'];

$respon = post_api(base_url("api/notification/RemoveNotificationByIdUser.php"), api_verify([
    "id_user" => $id_user,
    "id_notification" => $id
]));
if ($respon['status'] == "success")
    redirect(base_url());

redirect(base_url("client/purchased-lol"));
