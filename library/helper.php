<?php
function base_url($url = '')
{
    $a = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($a == 'http://localhost') {
        $a = 'http://localhost/Do-An-Thuc-Hanh-Cong-Nghe-Va-Dich-Vu-Web';
    }
    return $a . '/' . $url;
}
function base_url_admin($url = '')
{
    $a = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($a == 'http://localhost') {
        $a = 'http://localhost/Do-An-Thuc-Hanh-Cong-Nghe-Va-Dich-Vu-Web';
    }
    return $a . '/admin/' . $url;
}
function redirect($url, $time = 0)
{
    if ($time > 0)
        header("Refresh: $time; Url=$url");
    else {
        header("Location: $url");
        exit();
    }
}
function reload($time = 0)
{
    header("Refresh: $time");
    exit();
}
function input_post($key)
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : false;
}
function input_get($key)
{
    return isset($_GET[$key]) ? trim($_GET[$key]) : false;
}
function is_submit($key)
{
    return $_SERVER['REQUEST_METHOD'] == $key ? true : false;
}
function check_string($data)
{
    return htmlspecialchars(strip_tags($data));
}
function loadFileCss($directory, array $names)
{
    $result = "";
    foreach ($names as $name) {
        if (file_exists($directory . $name)) {
            $result .= "<link rel='stylesheet' href='" . base_url($directory . $name) . "'>";
        }
    }

    return $result;
}
function is_page($namePage)
{
    $url = $_SERVER['REQUEST_URI'];
    $url = explode("/", $url);
    if (end($url) == $namePage) return true;

    return false;
}
function json_encode_utf8($data)
{
    return json_encode($data, JSON_UNESCAPED_UNICODE);
}
function json_decode_utf8($json)
{
    return json_decode($json, false, 512, JSON_UNESCAPED_UNICODE);
}
function timeAgo($time_ago)
{
    $time_ago = empty($time_ago) ? 0 : $time_ago;
    if ($time_ago == 0) {
        return '--';
    }
    $time_ago   = date("Y-m-d H:i:s", $time_ago);
    $time_ago   = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed;
    $minutes    = round($time_elapsed / 60);
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400);
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds " . 'giây trước';
    }
    //Minutes
    elseif ($minutes <= 60) {
        return "$minutes " . 'phút trước';
    }
    //Hours
    elseif ($hours <= 24) {
        return "$hours " . 'tiếng trước';
    }
    //Days
    elseif ($days <= 7) {
        if ($days == 1) {
            return 'Hôm qua';
        } else {
            return "$days " . 'ngày trước';
        }
    }
    //Weeks
    elseif ($weeks <= 4.3) {
        return "$weeks " . 'tuần trước';
    }
    //Months
    elseif ($months <= 12) {
        return "$months " . 'tháng trước';
    }
    //Years
    else {
        return "$years " . 'năm trước';
    }
}
function array_map_length($data)
{
    $count = [];
    for ($i = 1; $i <= count($data); $i++) {
        array_push($count, $i);
    }

    return $count;
}
function discount($percent)
{
    return 1 - ($percent / 100);
}
function is_admin()
{
    if (!session_get("information")) return false;
    if (session_get("information")['role'] != 2) return false;

    return true;
}
function generate_string($input, $strength)
{
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
function random_string($length = 20)
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return generate_string($permitted_chars, $length);
}
function first_separator(string $data)
{
    return explode(",", $data)[0];
}
function list_separator(string $data)
{
    return explode(",", $data);
}
function check_image($data)
{
    $data = explode(".", $data);
    $extension = end($data);
    if (!check_types($extension)) {
        return false;
    }

    return true;
}
function check_types($type)
{
    switch ($type) {
        case "png":
        case "jpg":
        case "gif":
        case "jpeg":
            return "image";
        case "viettel":
        case "vinaphone":
        case "mobifone":
        case "vietnamobile":
        case "zing":
            return "card-type";
        case "10000":
        case "20000":
        case "50000":
        case "100000":
        case "200000":
        case "500000":
            return "card-money";
        default:
            return false;
    }
}
function api_verify($data = [])
{
    $result = [];
    if (isset($data)) {
        foreach ($data as $key => $value) {
            $result[$key] = $value;
        }
    }
    return $result;
}
function site($key)
{
    $settings = post_api(base_url("api/settings/GetAllSettings.php"), api_verify())->settings;

    foreach ($settings as $setting) {
        if ($setting->name == $key) {
            return $setting->value;
        }
    }
    return null;
}
function name_user($data)
{
    $array_names = explode(" ", trim($data));
    $last_name = array_pop($array_names);

    return [
        "first_name" => $array_names,
        "last_name" => $last_name
    ];
}
function upload_images($directory_check_image, $images)
{
    $result = [];
    $time = time();

    // Kiểm tra xem có file nào người dùng gửi vào có phải chuẩn là file image không
    for ($i = 0; $i < count($images['name']); $i++) {
        $target_file = $directory_check_image . $time . basename($images['name'][$i]);
        $image_file_type = check_image(strtolower($target_file));

        if (!$image_file_type) return 1;
    }
    // Exec save file
    for ($i = 0; $i < count($images['name']); $i++) {
        $target_file = $directory_check_image . $time . basename($images['name'][$i]);
        if (move_uploaded_file($images['tmp_name'][$i], $target_file)) {
            array_push($result, $directory_check_image . $time . basename($images['name'][$i]));
        } else {
            return 2;
        }
    }

    return $result;
}
function remove_upload_images($directory, $images)
{
    foreach ($images as $image) {
        if (file_exists($directory . $image)) {
            unlink($directory . $image) ? "" : show_notification("warning", "Vui lòng đừng nghịch bậy bạ!");
        }
    }
}
function upload_image($directory_check_image, $image)
{
    $time = time();
    $target_file = $directory_check_image . $time . basename($image['name']);
    $image_file_type = check_image(strtolower($target_file));
    if (!$image_file_type) return 1;
    if (!move_uploaded_file($image['tmp_name'], $target_file)) {
        return 2;
    }

    return $target_file;
}
function remove_upload_image($directory, $image)
{
    if (file_exists($directory . $image)) {
        unlink($directory . $image);
    }
}
function check_num_error($num_error, $message, $key, $data)
{
    $result = [];

    switch ($num_error) {
        case 0:
            $result = [
                "errCode" => $num_error,
                "status" => "success",
                "message" => $message
            ];
            if (!is_null($key)) $result[$key] = $data;

            return $result;
        case 1:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Thiếu tham số truyền vào"
            ];
        case 2:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tài khoản hoặc mật khẩu sai"
            ];
        case 3:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tên tài khoản vui lòng dài từ 5 đến 20 ký tự"
            ];
        case 4:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Mật khẩu vui lòng dài từ 8 đến 16 ký tự"
            ];
        case 5:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tên quá dài"
            ];
        case 6:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng đúng định dạng email"
            ];
        case 7:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Mật khẩu không trùng khớp"
            ];
        case 8:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tài khoản đã được sử dụng"
            ];
        case 9:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Id vui lòng phải là số"
            ];
        case 10:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy người dùng"
            ];
        case 11:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Limit vui lòng phải là số"
            ];
        case 12:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Limit start vui lòng phải là số"
            ];
        case 13:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Limit vui lòng phải lớn hơn 0"
            ];
        case 14:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Limit start vui lòng phải lớn hơn 0"
            ];
        case 15:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng set limit start lớn hơn 0"
            ];
        case 16:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng set limit start lớn hơn 0"
            ];
        case 17:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng tuổi phải là số và phải lớn hơn 0"
            ];
        case 18:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng đúng định dạng Email"
            ];
        case 19:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng số điện thoại phải là số"
            ];
        case 20:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng số tiền phải là số và phải lớn hơn 0"
            ];
        case 21:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Quyền hạng vui lòng phải là số và phải nằm trong 0 và 2"
            ];
        case 22:
            $result = [
                "errCode" => $num_error,
                "status" => "success",
                "message" => "Danh sách đang trống"
            ];
            if (!is_null($key)) $result[$key] = $data;

            return $result;
        case 23:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy danh mục nào"
            ];
        case 24:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tên danh mục đã tồn tại"
            ];
        case 25:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy danh mục"
            ];
        case 26:
            return [
                "errCode" => $num_error,
                "status" => "success",
                "message" => "Phần cài đặt đang trống vui lòng nhập thêm"
            ];
        case 27:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Chiết khấu vui lòng phải là số"
            ];
        case 28:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy đơn hàng"
            ];
        case 29:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Trạng thái bán chỉ được nhập T hoặc F"
            ];
        case 30:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tài khoản đã bị xoá hoặc bị lỗi"
            ];
        case 31:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy mã sản phẩm"
            ];
        case 32:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy tài khoản game"
            ];
        case 33:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Số lượng tướng vui lòng phải là số và lớn hơn 0"
            ];
        case 34:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Số lượng trang phục vui lòng phải là số và lớn hơn 0"
            ];
        case 35:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy hình ảnh"
            ];
        case 36:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy type của account"
            ];
        case 37:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Trạng thái xem vui chỉ vui lòng nhập T hoặc F"
            ];
        case 38:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Trạng thái vui lòng chỉ là S hoặc W hoặc F"
            ];
        case 39:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Không tìm thấy đơn nạp thẻ nào cả"
            ];
        case 40:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Trạng thái vui lòng nhập S hoặc F"
            ];
        case 41:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng nhập đúng định dạng thẻ"
            ];
        case 42:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Vui lòng nhập đúng định dạng tiền"
            ];
        case 43:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Mã thẻ và số serial không hợp lệ"
            ];
        case 44:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Trùng tên sản phẩm"
            ];
        case 45:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Số lượng vui lòng phải là số và phải lớn hơn 0"
            ];
        case 46:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Số lượng hàng đang không có đủ"
            ];
        case 47:
            return [
                "errCode" => $num_error,
                "status" => "error",
                "message" => "Tiền không đủ vui lòng nạp thêm"
            ];
        default:
            return [
                "status" => "error",
                "message" => "Có gì đó sai sai!"
            ];
    }
}
