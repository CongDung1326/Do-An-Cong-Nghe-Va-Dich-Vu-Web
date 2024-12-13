<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $notifications = get_api(base_url("api/notification/GetAllNotification.php?search=$search"))['notifications'];
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Không tìm thấy đơn hàng nào cả!</td>
    </tr>";

    array_map(function ($notification, $count) {
        global $result;

        $result .= "
            <tr>
                <td>$count</td>
                <td>{$notification->title}</td>
                <td>" . number_format($notification->money) . "đ</td>
                <td>{$notification->amount}</td>
                <td>{$notification->unique_code}</td>
                <td>" . timeAgo($notification->time) . "</td>
                <td>
                    <button class='failed'><a href='" . base_url_admin('order-remove/' . hash_encode($notification->id)) . "'>Xoá</a></button>
                </td>
            </tr>
        ";
    }, $notifications, array_map_length($notifications));

    echo !empty($result) ? $result : $not_found;
} else {
    $notifications = get_api(base_url("api/notification/GetAllNotification.php"))['notifications'];
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Danh sách đơn hàng đang chống!</td>
    </tr>";

    array_map(function ($notification, $count) {
        global $result;

        $result .= "
            <tr>
                <td>$count</td>
                <td>{$notification->title}</td>
                <td>" . number_format($notification->money) . "đ</td>
                <td>{$notification->amount}</td>
                <td>{$notification->unique_code}</td>
                <td>" . timeAgo($notification->time) . "</td>
                <td>
                    <button class='failed'><a href='" . base_url_admin('order-remove/' . hash_encode($notification->id)) . "'>Xoá</a></button>
                </td>
            </tr>
        ";
    }, $notifications, array_map_length($notifications));

    echo !empty($result) ? $result : $not_found;
}
