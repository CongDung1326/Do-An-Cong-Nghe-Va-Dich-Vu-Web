<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $users = post_api(base_url("api/user/GetAllUser.php?search=$search"), api_verify())->users;
    $result = "";
    $not_found = "<tr>
        <td colspan='5'>Không tìm thấy người dùng nào cả!</td>
    </tr>";

    array_map(function ($user, $count) {
        global $result;
        $is_admin = $user->role_id == '2' ? "Có" : "Không";

        $result .= "
            <tr>
                <td>$count</td>
                <td>
                    <ul>
                        <li><b>Tên đăng nhập:</b> $user->username</li>
                        <li><b>Địa chỉ Email:</b> $user->email</li>
                        <li><b>Số điện thoại:</b> $user->number_phone</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><b>Số dư khả dụng:</b> " . number_format($user->money) . "đ</li>
                        <li><b>Tổng số tiền nạp:</b> " . number_format($user->total_money) . "đ</li>
                    </ul>
                </td>
                <td>$is_admin</td>
                <td>
                    <button class='success'><a href='" . base_url_admin('user-edit/' . hash_encode($user->id)) . "'>Sửa</a></button>
                    <button class='failed' value='" . hash_encode($user->id) . "'>Xoá</button>
                </td>
            </tr>
        ";
    }, $users, array_map_length($users));

    echo !empty($result) ? $result : $not_found;
} else {
    $query = "SELECT u.id, u.username, u.email, u.number_phone, u.money, u.role_id FROM user u";
    $users = post_api(base_url("api/user/GetAllUser.php"), api_verify())->users;
    $result = "";
    $not_found = "<tr>
        <td colspan='5'>Danh sách người dùng đang chống!</td>
    </tr>";

    array_map(function ($user, $count) {
        global $result;
        $is_admin = $user->role_id == '2' ? "Có" : "Không";

        $result .= "
            <tr>
                <td>$count</td>
                <td>
                    <ul>
                        <li><b>Tên đăng nhập:</b> $user->username</li>
                        <li><b>Địa chỉ Email:</b> $user->email</li>
                        <li><b>Số điện thoại:</b> $user->number_phone</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li><b>Số dư khả dụng:</b> " . number_format($user->money) . "đ</li>
                        <li><b>Tổng số tiền nạp:</b> " . number_format($user->total_money) . "đ</li>
                    </ul>
                </td>
                <td>$is_admin</td>
                <td>
                    <button class='success'><a href='" . base_url_admin('user-edit/' . hash_encode($user->id)) . "'>Sửa</a></button>
                    <button class='failed' value='" . hash_encode($user->id) . "'>Xoá</button>
                </td>
            </tr>
        ";
    }, $users, array_map_length($users));

    echo !empty($result) ? $result : $not_found;
}
