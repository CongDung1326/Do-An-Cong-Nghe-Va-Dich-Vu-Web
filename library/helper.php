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
    return $a . '/admin' . $url;
}
function redirect($url)
{
    header("Location: $url");
    exit();
}
function reload($time)
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
