<?php
$money = 0;
if (session_get("information")) {
    $id = session_get("information")['id'];

    $result = post_api(base_url("api\user\GetUserById.php?id_user=$id"), api_verify())->user;
    $money = $result->money;
}

$showManageAdmin = session_get("information") ? session_get("information")['role'] == 2 : false;
?>

<div class="nav-container">
    <ul class="nav-left">
        <li class="shrink-nav" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></li>
        <li>Tiền: <?= number_format($money); ?>đ</li>
        <?= $showManageAdmin ? "<li><a href='" . base_url() . "'>Quay Lại</a></li>" : ""; ?>
        <li><a href="<?= session_get("information") ? base_url("client/logout") : base_url("client/login"); ?>"><?= session_get("information") ? "Đăng xuất" : "Đăng nhập"; ?></a></li>
    </ul>
    <ul class="nav-right">
        <li><i class="fa-solid fa-bell"></i></li>
        <li class="drop-menu">
            <img onclick="toggleDropMenu()" src="<?= session_get("information") ? base_url(session_get("information")['avatar']) : "https://pbs.twimg.com/profile_images/1701878932176351232/AlNU3WTK_400x400.jpg"; ?>" alt="">
            <ul class="hidden">
                <?php if (session_get("information")): ?>
                    <li><a href="">Thông tin người dùng</a></li>
                    <li><a href="<?= base_url("client/logout") ?>">Đăng xuất</a></li>
                <?php else: ?>
                    <li><a href="<?= base_url("client/login") ?>">Đăng Nhập</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li><?= session_get("information") ? "Xin chào, " . session_get("information")['name'] : "Bạn chưa đăng nhập"; ?></li>
    </ul>
</div>

<script>
    const toggleSidebar = () => {
        document.querySelector('.sidebar-container').classList.toggle("hidden");
    }
    const toggleDropMenu = () => {
        document.querySelector(".drop-menu ul").classList.toggle("hidden");
    }
</script>