<?php
$is_home = is_page("home");
$is_categories = is_page("categories");
$is_products = is_page("products");
$is_notifications = is_page("notifications");
$is_coupons = is_page("coupons");
$is_settings = is_page("settings");
?>

<!-- Side Nav START -->
<div class="side-nav expand-lg">
    <div class="side-nav-inner">
        <ul class="side-nav-menu">
            <li class="side-nav-header">
                <span>Navigation</span>
            </li>
            <li class="nav-item dropdown <?= $is_home ? "open" : "" ?>">
                <a href="#" class="dropdown-toggle">
                    <span class="icon-holder">
                        <i class="lni-dashboard"></i>
                    </span>
                    <span class="title">Home</span>
                    <span class="arrow">
                        <i class="lni-chevron-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu sub-down">
                    <li class="<?= $is_home ? "active" : "" ?>">
                        <a href="<?= base_url_admin_v2("home") ?>">Dashboard</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle">
                    <span class="icon-holder">
                        <i class="lni-dashboard"></i>
                    </span>
                    <span class="title">Danh Mục</span>
                    <span class="arrow">
                        <i class="lni-chevron-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu sub-down">
                    <li>
                        <a href="<?= base_url_admin_v2("") ?>">Categorys</a>
                        <a href="<?= base_url_admin_v2("") ?>">Products</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-toggle">
                    <span class="icon-holder">
                        <i class="lni-dashboard"></i>
                    </span>
                    <span class="title">Sản Phẩm</span>
                    <span class="arrow">
                        <i class="lni-chevron-right"></i>
                    </span>
                </a>
                <ul class="dropdown-menu sub-down">
                    <li>
                        <a href="<?= base_url_admin_v2("") ?>">Tài Khoản Games</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="<?= base_url_admin_v2("") ?>">
                    <span class="icon-holder">
                        <i class="lni-dashboard"></i>
                    </span>
                    <span class="title">Thông Báo</span>
                </a>
            </li>
            <li class="nav-item dropdown <?= $is_coupons ? "open" : "" ?>">
                <a href="<?= base_url_admin_v2("coupons") ?>">
                    <span class="icon-holder">
                        <i class="lni-ticket"></i>
                    </span>
                    <span class="title">Coupons</span>
                </a>
            </li>
            <li class="nav-item dropdown <?= $is_settings ? "open" : "" ?>">
                <a href="<?= base_url_admin_v2("settings") ?>">
                    <span class="icon-holder">
                        <i class="lni-cog"></i>
                    </span>
                    <span class="title">Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
</script>
<!-- Side Nav END -->