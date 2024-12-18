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
    $result = [
        "username" => API_USERNAME,
        "password" => API_PASSWORD,
    ];
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
    $array_names = explode(" ", $data);
    $last_name = array_pop($array_names);

    return [
        "first_name" => $array_names,
        "last_name" => $last_name
    ];
}
