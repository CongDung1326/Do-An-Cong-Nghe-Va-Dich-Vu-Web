<div class="history-puchased-container">
    <div class="title">Lịch Sử Mua Hàng</div>
    <table class="history-puchased">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Thanh Toán</th>
                <th>Thời Gian</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_id = session_get("information")['id'];
            $query = "SELECT b.id, b.store_account_children_id, b.amount, s.title, b.money as price, b.time 
            FROM notification_buy b, store_account_children s 
            WHERE b.store_account_children_id = s.id AND b.user_id = $user_id
            ORDER BY time DESC";

            $buys = $call_db->get_list($query);
            array_map(function ($buy, $count) {  ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $buy['title'] ?></td>
                    <td><?= $buy['amount'] ?></td>
                    <td><?= number_format($buy['price']) ?>đ</td>
                    <td><?= timeAgo($buy['time']) ?></td>
                    <td>
                        <form method="post" action="<?= base_url("client/check-puchased") ?>">
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