<div class="nav-container">
    <ul class="nav-left">
        <li class="shrink-nav"><i class="fa-solid fa-bars"></i></li>
        <li>Tiền: 0đ</li>
        <li><a href="<?= isset($_SESSION['information']) ? base_url("client/logout") : base_url("client/login"); ?>"><?= isset($_SESSION['information']) ? "Đăng xuất" : "Đăng nhập"; ?></a></li>
    </ul>
    <ul class="nav-right">
        <li><i class="fa-solid fa-bell"></i></li>
        <li><img src="<?= isset($_SESSION['information']) ? base_url($_SESSION['information']['avatar']) : "https://pbs.twimg.com/profile_images/1701878932176351232/AlNU3WTK_400x400.jpg"; ?>" alt=""></li>
        <li><?= isset($_SESSION['information']) ? "Xin chào, " . $_SESSION['information']['name'] : "Bạn chưa đăng nhập"; ?></li>
    </ul>
</div>