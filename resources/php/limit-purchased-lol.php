<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-pucharsed")) && input_get(hash_encode("limit"))) {
    $limit_pucharsed = input_get(hash_encode("limit-pucharsed"));
    $limit_max = input_get(hash_encode("limit"));
    $limit = $limit_max == 1 ? 0 : $limit_max * ($limit_pucharsed - 1);

    $user_id = session_get("information")['id'];
    $query = "SELECT b.id, b.money, b.time, l.number_char, l.number_skin, i.name as rank, l.id as number_account, b.unique_code
            FROM notification_buy b, account_lol l, images i
            WHERE b.account_lol_id = l.id AND b.user_id = $user_id AND l.rank_lol_id = i.id AND b.is_show = 'T'
            ORDER BY time DESC
            LIMIT $limit,$limit_max";
    $buys = $call_db->get_list($query);
    $result = "";
    $not_found = "<tr>
        <td colspan='9'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($buy, $count) {
        global $result;

        $result .= "
        <tr>
            <td>$count</td>
            <td>Acc Liên Minh #{$buy['number_account']}</td>
            <td>{$buy['number_char']}</td>
            <td>{$buy['number_skin']}</td>
            <td>{$buy['rank']}</td>
            <td>" . number_format($buy['money']) . "đ</td>
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
