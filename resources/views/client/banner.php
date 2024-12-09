<div class="banner-container">
    <div class="banner banner-left">
        <div class="title">Đơn hàng gần đây</div>
        <div class="product-recently">
            <table class="order">
                <tbody>
                    <?php
                    $notifications = get_api(base_url("api/notification/GetAllNotification.php?limit_start=10"))['notifications'];
                    array_map(function ($notification) { ?>
                        <tr>
                            <td class="infor"><i class="fa-solid fa-cart-shopping"></i> <span class="name-user"><?= $notification->name ?></span> mua <span class="amount"><?= $notification->amount ?></span> <span class="name-account"><?= $notification->title ?></span> -<span class="money"><?= number_format($notification->money) ?>đ</span></td>
                            <td class="time">
                                <p><?= timeAgo($notification->time); ?></p>
                            </td>
                        </tr>
                    <?php }, $notifications); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="banner banner-right">
        <div class="title">Nạp tiền gần đây</div>
        <div class="deposit-recently">
            <table class="order">
                <?php
                $banks = post_api(base_url("api\bank\GetAllBank.php?limit_start=10&status=S"), api_verify())['banks'];

                array_map(function ($bank) { ?>
                    <tr>
                        <td class="infor"><i class="fa-solid fa-money-bill"></i> <span class="name-user"><?= $bank->name ?></span> vừa nạp <span class="money"><?= number_format($bank->amount) ?>đ</span></td>
                        <td class="time">
                            <p><?= timeAgo($bank->time_created); ?></p>
                        </td>
                    </tr>
                <?php }, $banks); ?>
            </table>
        </div>
    </div>
</div>