<div class="account-puchased-container">
    <div class="title">Kiểm Tra Sản Phẩm</div>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tài Khoản</th>
                <th>Mật Khẩu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $amount = $call_db->get_row("SELECT amount FROM notification_buy WHERE id=$id_notification")['amount'];
            $query = "SELECT a.id, a.username, a.password 
            FROM notification_buy b, account a, store_account_children s
            WHERE (b.store_account_children_id = s.id 
            AND b.store_account_children_id = a.store_account_children_id) 
            AND b.id = $id_notification
            AND a.is_sold = 'T'
            LIMIT $num_of_times,$amount";
            $accounts = $call_db->get_list($query);

            array_map(function ($account, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $account['username'] ?></td>
                    <td><?= $account['password'] ?></td>
                </tr>
            <?php }, $accounts, array_map_length($accounts)); ?>
        </tbody>
    </table>
</div>