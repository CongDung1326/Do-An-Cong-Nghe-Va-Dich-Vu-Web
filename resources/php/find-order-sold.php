<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $query = "SELECT store_account_children_id, account_lol_id 
    FROM notification_buy
    WHERE unique_code LIKE '%$search%'
    ORDER BY time DESC";
    $notifications = $call_db->get_list($query);
    $orders = [];
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Không tìm thấy đơn hàng nào cả!</td>
    </tr>";

    array_map(function ($notification) {
        global $call_db, $orders;

        if (isset($notification['store_account_children_id'])) {
            $store_account_children_id = $notification['store_account_children_id'];
            $query = "SELECT b.id, b.amount, b.money, b.time, s.title, b.unique_code
            FROM store_account_children s, notification_buy b 
            WHERE s.id = b.store_account_children_id AND s.id = $store_account_children_id";

            array_push($orders, $call_db->get_row($query));
        } else {
            $account_lol_id = $notification['account_lol_id'];
            $query = "SELECT b.id, b.amount, b.money, b.time, a.id as title, b.unique_code
            FROM account_lol a, notification_buy b 
            WHERE a.id = b.account_lol_id AND a.id = $account_lol_id";

            array_push($orders, $call_db->get_row($query));
        }
    }, $notifications);

    array_map(function ($order, $count) {
        global $result;
        $order['title'] = is_numeric($order['title']) ? "Acc Liên Minh #" . $order['title'] : $order['title'];
        $remove = base_url_admin("remove-account-buyed/" . hash_encode($order['id']));

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$order['title']}</td>
            <td>" . number_format($order['money']) . "đ</td>
            <td>{$order['amount']}</td>
            <td>{$order['unique_code']}</td>
            <td>" . timeAgo($order['time']) . "</td>
            <td>
                <button class='failed'><a href='$remove'>Xoá</a></button>
            </td>
        </tr>
        ";
    }, $orders, array_map_length($orders));

    echo !empty($result) ? $result : $not_found;
} else {
    $id = session_get("information")['id'];
    $query = "SELECT store_account_children_id, account_lol_id 
    FROM notification_buy
    ORDER BY time DESC";
    $notifications = $call_db->get_list($query);
    $result = "";
    $orders = [];
    $not_found = "<tr>
        <td colspan='7'>Danh sách đơn hàng đang chống!</td>
    </tr>";

    array_map(function ($notification) {
        global $call_db, $orders;

        if (isset($notification['store_account_children_id'])) {
            $store_account_children_id = $notification['store_account_children_id'];
            $query = "SELECT b.id, b.amount, b.money, b.time, s.title, b.unique_code
            FROM store_account_children s, notification_buy b 
            WHERE s.id = b.store_account_children_id AND s.id = $store_account_children_id";

            array_push($orders, $call_db->get_row($query));
        } else {
            $account_lol_id = $notification['account_lol_id'];
            $query = "SELECT b.id, b.amount, b.money, b.time, a.id as title, b.unique_code
            FROM account_lol a, notification_buy b 
            WHERE a.id = b.account_lol_id AND a.id = $account_lol_id";

            array_push($orders, $call_db->get_row($query));
        }
    }, $notifications);

    array_map(function ($order, $count) {
        global $result;
        $order['title'] = is_numeric($order['title']) ? "Acc Liên Minh #" . $order['title'] : $order['title'];
        $remove = base_url_admin("remove-account-buyed/" . hash_encode($order['id']));

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$order['title']}</td>
            <td>" . number_format($order['money']) . "đ</td>
            <td>{$order['amount']}</td>
            <td>{$order['unique_code']}</td>
            <td>" . timeAgo($order['time']) . "</td>
            <td>
                <button class='failed'><a href='$remove'>Xoá</a></button>
            </td>
        </tr>
        ";
    }, $orders, array_map_length($orders));

    echo !empty($result) ? $result : $not_found;
}
