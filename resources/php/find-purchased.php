<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $user_id = session_get("information")['id'];
    $query = "SELECT b.id, b.store_account_children_id, b.amount, s.title, b.money as price, b.time, b.unique_code
            FROM notification_buy b, store_account_children s 
            WHERE b.store_account_children_id = s.id AND b.user_id = $user_id AND b.is_show = 'T' AND (s.title LIKE '%$search%' OR b.unique_code LIKE '%$search%')
            ORDER BY time DESC";

    $buys = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($buy, $count) {
        global $result;

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$buy['title']}</td>
            <td>{$buy['amount']}</td>
            <td>" . number_format($buy['price']) . "đ</td>
            <td>{$buy['unique_code']}</td>
            <td>" . timeAgo($buy['time']) . "</td>
            <td>
                <button class='check'><a href='" . base_url('client/check-purchased/' . hash_encode($buy['id'])) . "'>Kiểm Tra Sản Phẩm</a></button>
                <button class='delete' value='" . hash_encode($buy['id']) . "'>Xoá</button>
            </td>
        </tr>     
        ";
    }, $buys, array_map_length($buys));

    echo !empty($result) ? $result : $not_found;
} else {
    $user_id = session_get("information")['id'];
    $query = "SELECT b.id, b.store_account_children_id, b.amount, s.title, b.money as price, b.time, b.unique_code
            FROM notification_buy b, store_account_children s 
            WHERE b.store_account_children_id = s.id AND b.user_id = $user_id AND b.is_show = 'T'
            ORDER BY time DESC";
    $buys = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Danh sách tài khoản đang chống!</td>
    </tr>";

    array_map(function ($buy, $count) {
        global $result;

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$buy['title']}</td>
            <td>{$buy['amount']}</td>
            <td>" . number_format($buy['price']) . "đ</td>
            <td>{$buy['unique_code']}</td>
            <td>" . timeAgo($buy['time']) . "</td>
            <td>
                <button class='check'><a href='" . base_url('client/check-purchased/' . hash_encode($buy['id'])) . "'>Kiểm Tra Sản Phẩm</a></button>
                <button class='delete' value='" . hash_encode($buy['id']) . "'>Xoá</button>
            </td>
        </tr>     
        ";
    }, $buys, array_map_length($buys));

    echo !empty($result) ? $result : $not_found;
}
