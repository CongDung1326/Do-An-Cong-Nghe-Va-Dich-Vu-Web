<?php
$id = hash_decode(input_get("id"));

$query = "SELECT l.number_char, l.number_skin, l.price, l.image, l.account_id
        FROM account_lol l, account a
        WHERE l.account_id = a.id AND a.is_sold = 'F' AND a.type = 'lol' AND l.id = $id";
$account = $call_db->get_row($query);

if ($call_db->num_rows($query) != 1) show_notification("error", "Tài khoản này đã được mua!", base_url());
?>

<div class="form-buy-container">
    <form method="post" class="flex">
        <div class="title">Acc Liên Minh #1 - <?= $account['number_char'] ?> Tướng - <?= $account['number_skin'] ?> Skin</div>
        <div class="buy"><button name="buy_account" value="true" type="submit">Mua Với <?= number_format($account['price']) ?>đ</button></div>
    </form>
    <div class="form-buy">
        <div class="list-image">
            <?php
            $images = list_separator($account['image']);

            foreach ($images as $src) { ?>
                <img src="<?= base_url($src); ?>" alt="">
            <?php } ?>
        </div>
    </div>
</div>

<?php
if (input_post("buy_account")) {
    if (!session_get("information")) redirect(base_url("client/login"));

    $table_user = "user";
    $table_notification = "notification_buy";
    $table_account = "account";
    $price = $account['price'];
    $id_user = session_get("information")['id'];
    $id_account = $account['account_id'];
    $random = random_string();
    $money = $call_db->get_row("SELECT money FROM $table_user WHERE id=$id_user")['money'];

    if ($price > $money) show_notification("error", "Không đủ vui lòng nạp thêm!");
    $call_db->update($table_account, [
        "user_id" => $id_user,
        "unique_code" => $random,
        "is_sold" => "T",
    ], "id = $id_account AND is_sold = 'F'");
    $call_db->insert($table_notification, [
        "money" => $price,
        "amount" => 1,
        "user_id" => $id_user,
        "unique_code" => $random,
        "account_lol_id" => $id,
        "time" => time(),
        "is_show" => 'T'
    ]);
    $call_db->update($table_user, [
        "money" => $money - $price,
    ], "id = $id_user");

    show_notification("success", "Mua tài khoản thành công!", base_url("client/shop"));
}
?>