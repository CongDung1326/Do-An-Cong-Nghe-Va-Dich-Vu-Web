<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("limit-deposit")) && input_get(hash_encode("limit"))) {
    $limit_deposit = input_get(hash_encode("limit-deposit"));
    $limit_max = input_get(hash_encode("limit"));
    $limit = $limit_deposit == 1 ? "limit_start=$limit_max" : "limit_start=" . $limit_max * ($limit_deposit - 1) . "&limit=$limit_max";

    $banks = post_api(base_url("api\bank\GetAllBank.php?$limit&status=W"), api_verify())->banks;
    $result = "";
    $not_found = "<tr>
        <td colspan='9'>Không tìm thấy thẻ nào cả!</td>
    </tr>";

    array_map(function ($bank, $count) {
        global $result;

        $result .= "
            <tr>
                <td>$count</td>
                <td>{$bank->username}</td>
                <td>{$bank->name}</td>
                <td>{$bank->type}</td>
                <td>{$bank->serial}</td>
                <td>{$bank->pin}</td>
                <td>" . number_format($bank->amount) . "đ</td>
                <td>" . timeAgo($bank->time_created) . "</td>
                <td>
                    <form action='' method='post'>
                        <button class='success' name='deposit_type' type='submit' value='S'>Thành Công</button>
                        <button class='failed' name='deposit_type' type='submit' value='F'>Thất Bại</button>
                        <input type='text' value='" . hash_encode($bank->id) . "' name='deposit_type_id' hidden>
                        <input type='text' value='" . hash_encode($bank->id_user) . "' name='user_id' hidden>
                    </form>
                </td>
            </tr>
        ";
    }, $banks, array_map_length($banks));

    echo !empty($result) ? $result : $not_found;
}
