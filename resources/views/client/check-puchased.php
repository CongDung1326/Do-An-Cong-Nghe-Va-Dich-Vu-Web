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
    "account-puchased.css"
];


if (!session_get("information")) {
    redirect(base_url("client/login"));
}

if (input_post("puchased_method") && input_post("puchased_method_id")) {
    $puchased_method = check_string(input_post("puchased_method"));
    $puchased_method_id = check_string(hash_decode(input_post("puchased_method_id")));
    // $num_of_times = check_string(input_post("num_of_times"));
    $table = "notification_buy";

    if (!$puchased_method_id) {
        redirect(base_url());
    }
    if (!is_numeric($puchased_method_id)) {
        redirect(base_url());
    }
    if ($puchased_method != "check" && $puchased_method != "delete") {
        redirect(base_url());
    }

    if ($puchased_method == "check") {
        $id_notification = $puchased_method_id;
    } else {
        $call_db->remove($table, "id=$puchased_method_id");
        redirect(base_url("client/puchased"));
    }
} else {
    redirect(base_url());
}

require_once __DIR__ . "/header.php";
?>

<main class="home">
    <?php require_once __DIR__ . "/account-puchased.php" ?>
</main>

<?php
require_once __DIR__ . "/footer.php";
?>