<div class="history-puchased-container">
    <div class="title">Lịch Sử Mua Hàng</div>
    <table class="history-puchased">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản Phẩm</th>
                <th>Tướng</th>
                <th>Skin</th>
                <th>Rank</th>
                <th>Thanh Toán</th>
                <th>Thời Gian</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_id = session_get("information")['id'];
            $query = "SELECT b.id, b.money, b.time, l.number_char, l.number_skin, l.rank, l.id as number_account
            FROM notification_buy b, account_lol l
            WHERE b.account_lol_id = l.id AND b.user_id = $user_id
            ORDER BY time DESC";

            $buys = $call_db->get_list($query);
            array_map(function ($buy, $count) {  ?>
                <tr>
                    <td><?= $count ?></td>
                    <td>Acc Liên Minh #<?= $buy['number_account'] ?></td>
                    <td><?= $buy['number_char'] ?></td>
                    <td><?= $buy['number_skin'] ?></td>
                    <td><?= $buy['rank'] ?></td>
                    <td><?= number_format($buy['money']) ?>đ</td>
                    <td><?= timeAgo($buy['time']) ?></td>
                    <td>
                        <form method="post" action="<?= base_url("client/check-puchased-lol") ?>">
                            <button type="submit" name="puchased_method" value="check" class="check">Kiểm Tra Sản Phẩm</button>
                            <button type="submit" name="puchased_method" value="delete" class="delete">Xoá</button>
                            <input type="text" name="puchased_method_id" value="<?= hash_encode($buy['id']) ?>" hidden>
                        </form>
                    </td>
                </tr>
            <?php }, $buys, array_map_length($buys)); ?>
        </tbody>
    </table>
</div>