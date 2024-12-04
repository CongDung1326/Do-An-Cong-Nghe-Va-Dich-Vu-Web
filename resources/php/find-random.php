<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) AND a.is_sold = 'F' AND a.type = 'random' AND a.username LIKE '%$search%'";

    $accounts = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='6'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $is_sold = $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán";

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$account['username']}</td>
            <td>{$account['password']}</td>
            <td>{$account['title']}</td>
            <td>$is_sold</td>
            <td>
                <button class='success'><a href='" . base_url_admin("item-edit/" . hash_encode($account['id'])) . "'>Chỉnh Sửa</a></button>
                <button class='failed' value='" . hash_encode($account['id']) . "'>Xoá</button>
            </td>
        </tr>";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
} else {
    $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) AND a.is_sold = 'F' AND a.type = 'random'";
    $accounts = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='6'>Danh sách tài khoản đang chống!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $is_sold = $account['is_sold'] == "T" ? "Đã Bán" : "Chưa Bán";

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$account['username']}</td>
            <td>{$account['password']}</td>
            <td>{$account['title']}</td>
            <td>$is_sold</td>
            <td>
                <button class='success'><a href='" . base_url_admin("item-edit/" . hash_encode($account['id'])) . "'>Chỉnh Sửa</a></button>
                <button class='failed' value='" . hash_encode($account['id']) . "'>Xoá</button>
            </td>
        </tr>";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
}
