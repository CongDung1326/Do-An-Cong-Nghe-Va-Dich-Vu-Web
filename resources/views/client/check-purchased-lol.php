<?php
$body = [
    "title" => "Kiểm Tra Sản Phẩm",
    "desc" => $call_db->site("description"),
    "keyword" => $call_db->site("keyword"),
    "author" => $call_db->site("author")
];

$body['header'] = '';
$body['footer'] = '';

$css = [
    "index.css",
    "settings.css",
    "footer.css",
    "header.css",
    "nav.css",
    "index.css",
    "sidebar.css",
    "account-purchased.css"
];


if (!session_get("information")) {
    redirect(base_url("client/login"));
}

if (input_post("purchased_method") && input_post("purchased_method_id")) {
    $purchased_method = check_string(input_post("purchased_method"));
    $purchased_method_id = check_string(hash_decode(input_post("purchased_method_id")));
    // $num_of_times = check_string(input_post("num_of_times"));
    $table = "notification_buy";

    if (!$purchased_method_id) {
        redirect(base_url());
    }
    if (!is_numeric($purchased_method_id)) {
        redirect(base_url());
    }
    if ($purchased_method != "check" && $purchased_method != "delete") {
        redirect(base_url());
    }

    if ($purchased_method == "check") {
        $id_notification = $purchased_method_id;
    } else {
        $call_db->update($table, ["is_show" => "F"], "id=$purchased_method_id");
        redirect(base_url("client/purchased"));
    }
} else {
    redirect(base_url());
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/account-purchased-lol.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>