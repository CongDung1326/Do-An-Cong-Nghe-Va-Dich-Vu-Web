<div class="banner-container">
    <div class="banner banner-left">
        <div class="title">Đơn hàng gần đây</div>
        <div class="product-recently">
            <table class="order">
                <tbody>
                    <?php
                    $query = "SELECT n.id, n.amount, (n.amount * a.price) as money, u.name as name_user, a.title as name_account, n.time 
                    FROM notification_buy n, user u, store_account_children a 
                    WHERE n.user_id = u.id AND n.store_account_children_id = a.id
                    ORDER BY n.id DESC";

                    $notifications = $call_db->get_list($query);
                    array_map(function ($notification) { ?>
                        <tr>
                            <td class="infor"><i class="fa-solid fa-cart-shopping"></i> <span class="name-user"><?= $notification['name_user'] ?></span> mua <span class="amount"><?= $notification['amount'] ?></span> <span class="name-account"><?= $notification['name_account'] ?></span> -<span class="money"><?= number_format($notification['money']) ?>đ</span></td>
                            <td class="time">
                                <p><?= timeAgo($notification['time']); ?></p>
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
                $query = "SELECT b.amount, b.time_created as time, u.name FROM bank b, user u WHERE b.user_id = u.id AND b.status='S' ORDER BY time DESC;";
                $banks = $call_db->get_list($query);

                array_map(function ($bank) { ?>
                    <tr>
                        <td class="infor"><i class="fa-solid fa-money-bill"></i> <span class="name-user"><?= $bank['name'] ?></span> vừa nạp <span class="money"><?= number_format($bank['amount']) ?>đ</span></td>
                        <td class="time">
                            <p><?= timeAgo($bank['time']); ?></p>
                        </td>
                    </tr>
                <?php }, $banks); ?>
            </table>
        </div>
    </div>
</div>