<div class="list-item-container">
    <div class="tools">
        <div class="title">Thêm Hàng Liên Minh</div>
        <div class="add"><a href="<?= base_url_admin("lol-add") ?>">Thêm</a></div>
    </div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Tài Khoản</th>
            <th>Mật Khẩu</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Tướng</th>
            <th>Số Skin</th>
            <th>Rank</th>
            <th>Price</th>
            <th>Image</th>
            <th>Trạng Thái</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol';";
            $accounts = $call_db->get_list($query);

            array_map(function ($account, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $account['username'] ?></td>
                    <td><?= $account['password'] ?></td>
                    <td>Acc Liên Minh #<?= $account['name'] ?></td>
                    <td><?= $account['number_char'] ?></td>
                    <td><?= $account['number_skin'] ?></td>
                    <td><?= $account['rank'] ?></td>
                    <td><?= number_format($account['price']) ?>đ</td>
                    <td><a href="<?= base_url_admin("see-image-lol/" . $account['name']) ?>">See Image..</a></td>
                    <td><?= $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán"; ?></td>
                    <td>
                        <button class="success"><a href="<?= base_url_admin("lol-edit/" . hash_encode($account['id'])) ?>">Chỉnh Sửa</a></button>
                        <button class="failed"><a href="<?= base_url_admin("lol-remove/" . hash_encode($account['id'])) ?>">Xoá</a></button>
                    </td>
                </tr>
            <?php }, $accounts, array_map_length($accounts)); ?>
        </tbody>
    </table>
</div>