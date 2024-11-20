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
            $query = "SELECT b.id, b.store_account_children_id, b.amount, s.title, (s.price * b.amount) as price, b.time 
            FROM notification_buy b, store_account_children s 
            WHERE b.store_account_children_id = s.id AND b.user_id = $user_id";

            $buys = $call_db->get_list($query);
            array_map(function ($buy, $count, $count_array) {
                global $call_db; ?>
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
                            <?php
                            $query = "SELECT amount FROM notification_buy WHERE user_id = " . session_get("information")['id'];
                            $notification = $call_db->get_list($query);
                            $num_of_times = $count == 1 ? 0 : $notification[$count_array]['amount'];
                            ?>
                            <input type="text" name="num_of_times" value="<?= $num_of_times ?>" hidden>
                        </form>
                    </td>
                </tr>
            <?php }, $buys, array_map_length($buys), array_map_length_array($buys)); ?>
        </tbody>
    </table>
</div>