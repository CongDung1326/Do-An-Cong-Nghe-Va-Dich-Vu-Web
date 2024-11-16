<?php
$money = 0;
if (session_get("information")) {
    $id = session_get("information")['id'];

    $query = "SELECT money FROM user WHERE id=$id";
    $result = $call_db->get_row($query);
    $money = $result['money'];
}
?>

<div class="nav-container">
    <ul class="nav-left">
        <li class="shrink-nav"><i class="fa-solid fa-bars"></i></li>
        <li>Tiền: <?= number_format($money); ?>đ</li>
        <li><a href="<?= session_get("information") ? base_url("client/logout") : base_url("client/login"); ?>"><?= session_get("information") ? "Đăng xuất" : "Đăng nhập"; ?></a></li>
    </ul>
    <ul class="nav-right">
        <li><i class="fa-solid fa-bell"></i></li>
        <li><img src="<?= session_get("information") ? base_url(session_get("information")['avatar']) : "https://pbs.twimg.com/profile_images/1701878932176351232/AlNU3WTK_400x400.jpg"; ?>" alt=""></li>
        <li><?= session_get("information") ? "Xin chào, " . session_get("information")['name'] : "Bạn chưa đăng nhập"; ?></li>
    </ul>
</div>