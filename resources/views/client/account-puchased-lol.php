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
            $unique_code = $call_db->get_row("SELECT unique_code FROM notification_buy WHERE id=$id_notification")['unique_code'];
            $query = "SELECT a.id, a.username, a.password 
            FROM notification_buy b, account a, account_lol l
            WHERE (b.account_lol_id = l.id)
            AND b.id = $id_notification
            AND a.unique_code = '$unique_code'
            AND a.is_sold = 'T'
            AND a.type = 'lol'";
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