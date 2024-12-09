<?php
include_once __DIR__ . "/../../config.php";

if (input_get(hash_encode("search"))) {
    $search = input_get(hash_encode("search"));
    $id = session_get("information")['id'];
    $banks = post_api(base_url("api\bank\GetAllBankByIdUser.php?search=$search"), api_verify(["id_user" => $id]))['banks'];
    $result = "";
    $not_found = "<tr>
        <td colspan='8'>Không tìm thấy nhà mạng nào cả!</td>
    </tr>";

    array_map(function ($bank, $count) {
        global $result;

        $status = "";
        switch (strtolower($bank->status)) {
            case "s":
                $status = "thành công";
                break;
            case "w":
                $status = "đang đợi";
                break;
            case "f":
                $status = "không thành công";
                break;
        }
        $result .= "
            <tr>
                <td>$count</td>
                <td>{$bank->type}</td>
                <td>" . number_format($bank->amount) . "đ</td>
                <td>{$bank->serial}</td>
                <td>{$bank->pin}</td>
                <td>$status</td>
                <td>" . timeAgo($bank->time_created) . "</td>
                <td>{$bank->comment}</td>
            </tr>
        ";
    }, $banks, array_map_length($banks));

    echo !empty($result) ? $result : $not_found;
} else {
    $id = session_get("information")['id'];
    $banks = post_api(base_url("api\bank\GetAllBankByIdUser.php"), api_verify(["id_user" => $id]))['banks'];
    $result = "";
    $not_found = "<tr>
        <td colspan='8'>Danh sách nhà mạng đang chống!</td>
    </tr>";

    array_map(function ($bank, $count) {
        global $result;

        $status = "";
        switch (strtolower($bank->status)) {
            case "s":
                $status = "thành công";
                break;
            case "w":
                $status = "đang đợi";
                break;
            case "f":
                $status = "không thành công";
                break;
        }
        $result .= "
            <tr>
                <td>$count</td>
                <td>{$bank->type}</td>
                <td>" . number_format($bank->amount) . "đ</td>
                <td>{$bank->serial}</td>
                <td>{$bank->pin}</td>
                <td>$status</td>
                <td>" . timeAgo($bank->time_created) . "</td>
                <td>{$bank->comment}</td>
            </tr>
        ";
    }, $banks, array_map_length($banks));

    echo !empty($result) ? $result : $not_found;
}
