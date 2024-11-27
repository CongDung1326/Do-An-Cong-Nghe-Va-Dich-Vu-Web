<div class="user-list-container">
    <div class="title">Quản Lý Người Dùng</div>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tài Khoản</th>
                <th>Ví</th>
                <th>Admin</th>
                <th>Chức Năng</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $queryUser = "SELECT u.id, u.username, u.email, u.number_phone, u.money, u.role_id FROM user u";
            $users = $call_db->get_list($queryUser);
            array_map(function ($user, $count) {
                global $call_db;
            ?>
                <tr>
                    <td><?= $count ?></td>
                    <td>
                        <ul>
                            <li><b>Tên đăng nhập:</b> <?= $user['username'] ?></li>
                            <li><b>Địa chỉ Email:</b> <?= $user['email'] ?></li>
                            <li><b>Số điện thoại:</b> <?= $user['number_phone'] ?></li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li><b>Số dư khả dụng:</b> <?= number_format($user['money']) ?>đ</li>
                            <?php
                            $sum = $call_db->get_row("SELECT SUM(amount) as money_sum FROM bank WHERE user_id = " . $user['id'] . " AND status='S'");
                            ?>
                            <li><b>Tổng số tiền nạp:</b> <?= number_format($sum['money_sum']) ?>đ</li>
                        </ul>
                    </td>
                    <td><?= $user['role_id'] == '2' ? "Có" : "Không"; ?></td>
                    <td>
                        <button class="success"><a href="<?= base_url_admin("user-edit/" . hash_encode($user['id'])) ?>">Sửa</a></button>
                        <button class="failed"><a href="<?= base_url_admin("user-remove/" . hash_encode($user['id'])) ?>">Xoá</a></button>
                    </td>
                </tr>
            <?php }, $users, array_map_length($users)); ?>
        </tbody>
    </table>
</div>