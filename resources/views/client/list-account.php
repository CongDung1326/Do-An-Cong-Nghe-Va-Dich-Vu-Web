<div class="list-account-container">
    <div class="title">Bán Acc Liên Minh Huyền Thoại</div>
    <div class="wrapper">
        <?php
        $query = "SELECT l.id, l.number_char, l.number_skin, i.name as rank, i.href, l.price, l.image
        FROM account_lol l, account a, images i
        WHERE (l.account_id = a.id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol'";
        $accounts = $call_db->get_list($query);

        array_map(function ($account) { ?>
            <div class="list-account">
                <div class="top">
                    <div class="image">
                        <img src="<?= base_url(first_separator($account['image'])) ?>" alt="">
                        <a class="show" href="<?= base_url("client/buy/" . hash_encode($account['id'])) ?>">Xem thêm..</a>
                    </div>
                </div>
                <div class="center">Acc Liên Minh #<?= $account['id']; ?></div>
                <div class="bottom">
                    <div class="left">
                        <div class="rank">Rank: <?= $account['rank'] ?></div>
                        <div class="number_char">Tướng: <?= $account['number_char'] ?></div>
                        <div class="number_skin">Skin: <?= $account['number_skin'] ?></div>
                    </div>
                    <div class="right">
                        <div class="image-rank"><img src="<?= base_url($account['href']) ?>" alt=""></div>
                        <div class="price"><?= number_format($account['price']) ?>đ</div>
                    </div>
                </div>

                <button type="submit">MUA NGAY</button>
            </div>
        <?php }, $accounts); ?>
    </div>
</div>