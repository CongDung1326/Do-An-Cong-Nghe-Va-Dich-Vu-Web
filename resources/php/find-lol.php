<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol'
            AND a.username LIKE '%$search%'";

    $accounts = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='11'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $see_images = base_url_admin("see-image-lol/" . $account['name']);
        $is_sold = $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán";
        $edit = base_url_admin("lol-edit/" . hash_encode($account['id']));

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$account['username']}</td>
            <td>{$account['password']}</td>
            <td>Acc Liên Minh #{$account['name']}</td>
            <td>{$account['number_char']}</td>
            <td>{$account['number_skin']}</td>
            <td>{$account['rank']}</td>
            <td>" . number_format($account['price']) . "đ</td>
            <td><a href='$see_images'>See Image..</a></td>
            <td>$is_sold</td>
            <td>
                <button class='success'><a href='$edit'>Chỉnh Sửa</a></button>
                <button class='failed' value='" . hash_encode($account['id']) . "'>Xoá</button>
            </td>
        </tr>
        ";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
} else {
    $query = "SELECT a.id, a.username, a.password, l.id as name, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol'";
    $accounts = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='11'>Danh sách tài khoản đang chống!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $see_images = base_url_admin("see-image-lol/" . $account['name']);
        $is_sold = $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán";
        $edit = base_url_admin("lol-edit/" . hash_encode($account['id']));

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$account['username']}</td>
            <td>{$account['password']}</td>
            <td>Acc Liên Minh #{$account['name']}</td>
            <td>{$account['number_char']}</td>
            <td>{$account['number_skin']}</td>
            <td>{$account['rank']}</td>
            <td>" . number_format($account['price']) . "đ</td>
            <td><a href='$see_images'>See Image..</a></td>
            <td>$is_sold</td>
            <td>
                <button class='success'><a href='$edit'>Chỉnh Sửa</a></button>
                <button class='failed' value='" . hash_encode($account['id']) . "'>Xoá</button>
            </td>
        </tr>
        ";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
}
