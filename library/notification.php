<?php
function get_html($type = "success" | "error" | "warning", $icon = "", $text = "")
{
    echo "
    <div class='notification-container'>
        <div class='notification $type'>
            <div class='icon'><i class='$icon'></i></div>
            <div class='title'>$text</div>
        </div>
    </div>
    ";
}
function get_css()
{
    $link = base_url("public/css/common/notification.css");

    echo "<link rel='stylesheet' href='$link'>";
}
function show_notification($type = "success" | "error" | "warning", $text, $url = "")
{
    switch ($type) {
        case "success":
            get_html("success", "fa-solid fa-check", $text);
            get_css();
            $url == "" ? reload("2") : redirect($url);
            break;
        case "error":
            get_html("error", "fa-solid fa-xmark", $text);
            get_css();
            $url == "" ? reload("2") : redirect($url);
            break;
        case "warning":
            get_html("warning", "fa-solid fa-exclamation", $text);
            get_css();
            $url == "" ? reload("2") : redirect($url);
            break;
        default:
            break;
    }
}
