<div class="list-deposit-container">
    <div class="title">Quản Lý Nạp Thẻ</div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Tài Khoản</th>
            <th>Họ Tên</th>
            <th>Loại Thẻ</th>
            <th>Serial</th>
            <th>Pin</th>
            <th>Số Tiền</th>
            <th>Thời Gian</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.user_id, u.name, u.username, b.time_created as time FROM bank b, user u WHERE b.user_id = u.id AND b.status = 'W'";
            $banks = $call_db->get_list($query);

            array_map(function ($bank, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $bank['username'] ?></td>
                    <td><?= $bank['name'] ?></td>
                    <td><?= $bank['type'] ?></td>
                    <td><?= $bank['serial'] ?></td>
                    <td><?= $bank['pin'] ?></td>
                    <td><?= number_format($bank['amount']) ?>đ</td>
                    <td><?= timeAgo($bank['time']) ?></td>
                    <td>
                        <form action="" method="post">
                            <button class="success" name="deposit_type" type="submit" value="S">Thành Công</button>
                            <button class="failed" name="deposit_type" type="submit" value="F">Thất Bại</button>
                            <input type="text" value="<?= hash_encode($bank['id']) ?>" name="deposit_type_id" hidden>
                            <input type="text" value="<?= hash_encode($bank['user_id']) ?>" name="user_id" hidden>
                        </form>
                    </td>
                </tr>
            <?php }, $banks, array_map_length($banks)); ?>
        </tbody>
    </table>
</div>

<?php
if (input_post("deposit_type") && input_post("deposit_type_id") && input_post("user_id")) {
    $deposit_type = check_string(input_post("deposit_type"));
    $deposit_type_id = hash_decode(check_string(input_post("deposit_type_id")));
    $user_id = hash_decode(check_string(input_post("user_id")));
    $tableBank = "bank";
    $tableUser = "user";

    if (!$deposit_type_id) {
        show_notification("error", "Lỗi rồi bạn ơi!");
    }

    $queryUpdateBank = $call_db->update($tableBank, [
        "status" => $deposit_type
    ], "id=$deposit_type_id");

    if ($deposit_type == "S") {
        $queryGetMoney = "SELECT amount FROM $tableBank WHERE id=$deposit_type_id AND status='S'";
        $queryGetMoneyUser = "SELECT money FROM $tableUser WHERE id=$user_id";
        $bank = $call_db->get_row($queryGetMoney);
        $user = $call_db->get_row($queryGetMoneyUser);
        $queryUpdateUser = $call_db->update($tableUser, [
            'money' => $user['money'] + $bank['amount']
        ], "id=$user_id");
    }

    reload();
}
?>