<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-random")) && input_get(hash_encode("limit"))) {
    $limit_random = input_get(hash_encode("limit-random"));
    $limit_max = input_get(hash_encode("limit"));
    $limit = $limit_max == 1 ? 0 : $limit_max * ($limit_random - 1);

    $query = "SELECT a.id, a.username, a.password, s.title, a.is_sold 
            FROM account a, store_account_children s 
            WHERE (a.store_account_children_id = s.id) AND a.is_sold = 'F' AND a.type = 'random' 
            LIMIT $limit,$limit_max";
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
