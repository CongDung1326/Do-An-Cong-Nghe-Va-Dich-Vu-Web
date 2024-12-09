<?php
$id = hash_decode(input_get("id"));

$respon = get_api(base_url("api/account/GetAccountLOLByIdAccount.php?id=$id"));
$account = $respon['account'];

if (empty($account)) show_notification("error", "Tài khoản này đã được mua!", base_url());
?>

<div class="form-buy-container">
    <form method="post" class="flex">
        <div class="title">Acc Liên Minh #1 - <?= $account->number_char ?> Tướng - <?= $account->number_skin ?> Skin</div>
        <div class="buy"><button name="buy_account" value="true" type="submit">Mua Với <?= number_format($account->price) ?>đ</button></div>
    </form>
    <div class="form-buy">
        <div class="list-image">
            <?php
            $images = list_separator($account->image);

            foreach ($images as $src) { ?>
                <img src="<?= base_url($src); ?>" alt="">
            <?php } ?>
        </div>
    </div>
</div>

<?php
if (input_post("buy_account")) {
    $id_user = session_get("information")['id'];
    $data = [
        "id_user" => $id_user,
        "id_account" => $id
    ];
    $respon = post_api(base_url("api/account/BuyAccountLOL.php"), $data);
    if ($respon['status'] == "error") show_notification("warning", $respon['message']);
    else
        show_notification("success", "Mua tài khoản thành công!", base_url("client/shop"));
}
?>