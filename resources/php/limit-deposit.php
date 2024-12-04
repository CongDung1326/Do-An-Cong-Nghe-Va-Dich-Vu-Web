<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-deposit")) && input_get(hash_encode("limit"))) {
    $limit_user = input_get(hash_encode("limit-deposit"));
    $limit_max = input_get(hash_encode("limit"));
    $limit = $limit_max == 1 ? 0 : $limit_max * ($limit_user - 1);

    $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.user_id, u.name, u.username, b.time_created as time FROM bank b, user u WHERE b.user_id = u.id AND b.status = 'W' LIMIT $limit,$limit_max";
    $banks = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='9'>Không tìm thấy người dùng nào cả!</td>
    </tr>";

    array_map(function ($bank, $count) {
        global $result;

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$bank['username']}</td>
            <td>{$bank['name']}</td>
            <td>{$bank['type']}</td>
            <td>{$bank['serial']}</td>
            <td>{$bank['pin']}</td>
            <td>" . number_format($bank['amount']) . "đ</td>
            <td>" . timeAgo($bank['time']) . "</td>
            <td>
                <form method='post'>
                    <button class='success' name='deposit_type' type='submit' value='S'>Thành Công</button>
                    <button class='failed' name='deposit_type' type='submit' value='F'>Thất Bại</button>
                    <input type='text' value='" . hash_encode($bank['id']) . "' name='deposit_type_id' hidden>
                    <input type='text' value='" . hash_encode($bank['user_id']) . "' name='user_id' hidden>
                </form>
            </td>
        </tr>
        ";
    }, $banks, array_map_length($banks));

    echo !empty($result) ? $result : $not_found;
}
