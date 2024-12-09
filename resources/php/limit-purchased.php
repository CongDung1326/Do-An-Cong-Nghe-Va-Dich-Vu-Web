<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-purcharsed")) && input_get(hash_encode("limit"))) {
    $limit_purcharsed = input_get(hash_encode("limit-purcharsed"));
    $limit_max = input_get(hash_encode("limit"));

    $limit = $limit_purcharsed == 1 ? "limit_start=$limit_max" : "limit_start=" . $limit_max * ($limit_purcharsed - 1) . "&limit=$limit_max";

    $user_id = session_get("information")['id'];
    $buys = post_api(base_url("api/notification/GetAllNotificationRandom.php?is_show=T&$limit"), api_verify(["id_user" => $user_id]))['notifications'];
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($buy, $count) {
        global $result;

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$buy->title}</td>
            <td>{$buy->amount}</td>
            <td>" . number_format($buy->money) . "đ</td>
            <td>{$buy->unique_code}</td>
            <td>" . timeAgo($buy->time) . "</td>
            <td>
                <button class='check'><a href='" . base_url('client/check-purchased/' . hash_encode($buy->id)) . "'>Kiểm Tra Sản Phẩm</a></button>
                <button class='delete' value='" . hash_encode($buy->id) . "'>Xoá</button>
            </td>
        </tr>     
        ";
    }, $buys, array_map_length($buys));

    echo !empty($result) ? $result : $not_found;
}
