<?php
$buyed = $call_db->get_row("SELECT COUNT(*) as buyed FROM notification_buy")['buyed'];
$account_sold = $call_db->get_row("SELECT COUNT(*) as sold FROM account WHERE is_sold = 'T'")['sold'];
$count_user = $call_db->get_row("SELECT COUNT(*) as users FROM user")['users'];
$sold = $call_db->get_row("SELECT SUM(money) as money FROM notification_buy")['money'];
?>

<div class="banner-container">
    <div class="title">Doanh Số</div>
    <div class="banner">
        <div class="banner-sold">
            <div class="left">
                <div class="amount"><?= number_format($buyed); ?></div>
                <div class="title">Đơn hàng đã bán</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="move"><a href="">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-account">
            <div class="left">
                <div class="amount"><?= number_format($account_sold); ?></div>
                <div class="title">Tài khoản đã bán</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <div class="move"><a href="">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-user">
            <div class="left">
                <div class="amount"><?= number_format($count_user); ?></div>
                <div class="title">Tổng thành viên</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="move"><a href="">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
        <div class="banner-money">
            <div class="left">
                <div class="amount"><?= number_format($sold); ?>đ</div>
                <div class="title">Doanh thu đơn hàng</div>
            </div>
            <div class="right">
                <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="move"><a href="">Xem thêm <i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
    </div>
</div>