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
