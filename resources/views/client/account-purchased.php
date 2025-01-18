<div class="account-purchased-container">
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
            $id_user = session_get("information")['id'];

            $respon = post_api(base_url("api/account/GetAllAccountRandom.php?is_sold=T"), api_verify([
                "id_user" => $id_user,
                "id_notification" => $id_notification
            ]));
            if ($respon->errCode == 11 && $respon->status == "error") {
                show_notification("error", $respon->message, base_url());
                return;
            }

            $accounts = $respon->accounts;

            array_map(function ($account, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $account->username ?></td>
                    <td><?= $account->password ?></td>
                </tr>
            <?php }, $accounts, array_map_length($accounts)); ?>
        </tbody>
    </table>
</div>