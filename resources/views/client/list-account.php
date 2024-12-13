<div class="list-account-container">
    <div class="title">Bán Acc Liên Minh Huyền Thoại</div>
    <div class="wrapper">
        <?php
        $respon = post_api(base_url("api/account/GetAllAccountLOL.php?is_sold=F"), api_verify());
        $accounts = $respon['accounts'];

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

                <button type="submit">MUA NGAY</button>
            </div>
        <?php }, $accounts); ?>
    </div>
</div>