<?php
$banners = post_api(base_url("api/settings/GetDataBanner.php"), api_verify())->data;
?>

<div class="banner-container">
    <div class="title">Doanh Số</div>
    <div class="banner">
        <div class="banner-sold">
            <div class="left">
                <div class="amount"><?= number_format($banners->buyed); ?></div>
                <div class="title">Đơn hàng đã bán</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="move"><a href="<?= base_url_admin("manage-order-sold") ?>">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-account">
            <div class="left">
                <div class="amount"><?= number_format($banners->account_sold); ?></div>
                <div class="title">Tài khoản đã bán</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <div class="move"><a href="<?= base_url_admin("manage-account-buyed") ?>">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-user">
            <div class="left">
                <div class="amount"><?= number_format($banners->count_user); ?></div>
                <div class="title">Tổng thành viên</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="move"><a href="<?= base_url_admin("manage-user") ?>">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-money">
            <div class="left">
                <div class="amount"><?= number_format($banners->sold); ?>đ</div>
                <div class="title">Doanh thu đơn hàng</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-money-bill"></i>
            </div>
            <!-- <div class="move"><a href="">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div> -->
        </div>
    </div>
</div>