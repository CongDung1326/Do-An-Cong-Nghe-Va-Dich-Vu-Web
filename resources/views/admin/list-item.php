<div class="list-item-container">
    <div class="tools">
        <div class="title">Thêm Hàng</div>
        <div class="add"><a href="<?= base_url_admin("item-add") ?>">Thêm</a></div>
    </div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Tài Khoản</th>
            <th>Mật Khẩu</th>
            <th>Tên Sản Phẩm</th>
            <th>Trạng Thái</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) AND a.is_sold = 'F';";
            $accounts = $call_db->get_list($query);

            array_map(function ($account, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $account['username'] ?></td>
                    <td><?= $account['password'] ?></td>
                    <td><?= $account['title'] ?></td>
                    <td><?= $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán"; ?></td>
                    <td>
                        <button class="success"><a href="<?= base_url_admin("item-edit/" . hash_encode($account['id'])) ?>">Chỉnh Sửa</a></button>
                        <button class="failed"><a href="">Xoá</a></button>
                    </td>
                </tr>
            <?php }, $accounts, array_map_length($accounts)); ?>
        </tbody>
    </table>
</div>