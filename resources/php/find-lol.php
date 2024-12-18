<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $accounts = post_api(base_url("api/account/GetAllAccountLOL.php?is_sold=F&search=$search"), api_verify())->accounts;
    $result = "";
    $not_found = "<tr>
        <td colspan='11'>Không tìm thấy tài khoản nào cả!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $is_sold = $account->is_sold == 'T' ? 'Đã Bán' : 'Chưa Bán';

        $result .= "
            <tr>
                <td>$count</td>
                <td>$account->username</td>
                <td>$account->password</td>
                <td>Acc Liên Minh #$account->name</td>
                <td>$account->number_char</td>
                <td>$account->number_skin</td>
                <td>$account->rank</td>
                <td>" . number_format($account->price) . "đ</td>
                <td><a href='" . base_url_admin('see-image-lol/' . $account->name) . "'>See Image..</a></td>
                <td>$is_sold</td>
                <td>
                    <button class='success'><a href='" . base_url_admin('lol-edit/' . hash_encode($account->id)) . "'>Chỉnh Sửa</a></button>
                    <button class='failed' value='" . hash_encode($account->id) . "'>Xoá</button>
                </td>
            </tr>
        ";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
} else {
    $accounts = post_api(base_url("api/account/GetAllAccountLOL.php?is_sold=F"), api_verify())->accounts;
    $result = "";
    $not_found = "<tr>
        <td colspan='11'>Danh sách tài khoản đang chống!</td>
    </tr>";

    array_map(function ($account, $count) {
        global $result;
        $is_sold = $account->is_sold == 'T' ? 'Đã Bán' : 'Chưa Bán';

        $result .= "
            <tr>
                <td>$count</td>
                <td>$account->username</td>
                <td>$account->password</td>
                <td>Acc Liên Minh #$account->name</td>
                <td>$account->number_char</td>
                <td>$account->number_skin</td>
                <td>$account->rank</td>
                <td>" . number_format($account->price) . "đ</td>
                <td><a href='" . base_url_admin('see-image-lol/' . $account->name) . "'>See Image..</a></td>
                <td>$is_sold</td>
                <td>
                    <button class='success'><a href='" . base_url_admin('lol-edit/' . hash_encode($account->id)) . "'>Chỉnh Sửa</a></button>
                    <button class='failed' value='" . hash_encode($account->id) . "'>Xoá</button>
                </td>
            </tr>
        ";
    }, $accounts, array_map_length($accounts));

    echo !empty($result) ? $result : $not_found;
}
