<div class="list-account-container">
    <div class="title">Bán Acc Liên Minh Huyền Thoại</div>
    <div class="wrapper">
        <?php
        $respon = post_api(base_url("api/account/GetAllAccountLOL.php?is_sold=F"), api_verify());
        $accounts = $respon->accounts;

        array_map(function ($account) { ?>
            <div class="list-account">
                <div class="top">
                    <div class="image">
                        <img src="<?= base_url(first_separator($account->image)) ?>" alt="">
                        <a class="show" href="<?= base_url("client/buy/" . hash_encode($account->id)) ?>">Xem thêm..</a>
                    </div>
                </div>
                <div class="center">Acc Liên Minh #<?= $account->id; ?></div>
                <div class="bottom">
                    <div class="left">
                        <div class="rank">Rank: <?= $account->rank ?></div>
                        <div class="number_char">Tướng: <?= $account->number_char ?></div>
                        <div class="number_skin">Skin: <?= $account->number_skin ?></div>
                    </div>
                    <div class="right">
                        <div class="image-rank"><img src="<?= base_url($account->href) ?>" alt=""></div>
                        <div class="price"><?= number_format($account->price) ?>đ</div>
                    </div>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="buy_account_id" value="<?= hash_encode($account->id) ?>">
                    <button type="submit" name="buy_account" value="true">MUA NGAY</button>
                </form>
            </div>
        <?php }, $accounts); ?>

        <?php
        $not_found = [
            "list" => $accounts,
            "title" => "Danh sách đang trống"
        ];

        require_once __DIR__ . "/../common/not-found-product.php";
        ?>
    </div>
</div>

<?php
if (input_post("buy_account") && input_post("buy_account_id")) {
    if (!session_get("information"))
        redirect(base_url("client/login"));
    $id_user = session_get("information")['id'];
    $id_account = hash_decode(input_post("buy_account_id"));

    $data = [
        "id_user" => $id_user,
        "id_account" => $id_account
    ];
    $respon = post_api(base_url("api/account/BuyAccountLOL.php"), $data);
    if ($respon->status == "error") show_notification("warning", $respon->message);
    else
        show_notification("success", "Mua tài khoản thành công!", base_url("client/shop"));
}
?>