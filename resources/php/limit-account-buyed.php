<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-account")) && input_get(hash_encode("limit"))) {
    $limit_account = input_get(hash_encode("limit-account"));
    $limit_max = input_get(hash_encode("limit"));
    $limit = $limit_account == 1 ? "limit_start=$limit_max" : "limit_start=" . $limit_max * ($limit_account - 1) . "&limit=$limit_max";

    $accounts = post_api(base_url("api/account/GetAllAccountBuyed.php?$limit"), api_verify())->accounts;
    $result = "";
    $not_found = "<tr>
        <td colspan='7'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $is_sold = $account->is_sold == "T" ? "Đã Bán" : "Chưa Bán";
        $edit = base_url_admin("edit-account-buyed/" . hash_encode($account->id));

        $result .= "
        <tr>
            <td>$count</td>
            <td>{$account->username}</td>
            <td>{$account->password}</td>
            <td>{$account->user_username}</td>
            <td>{$account->unique_code}</td>
            <td>$is_sold</td>
            <td>
                <button class='success'><a href='$edit'>Chỉnh Sửa</a></button>
                <button class='failed' value='" . hash_encode($account->id) . "'>Xoá</button>
            </td>
        </tr>
        ";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
}
