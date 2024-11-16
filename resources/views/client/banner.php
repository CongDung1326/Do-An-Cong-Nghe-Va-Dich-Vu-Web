<div class="banner-container">
    <div class="banner banner-left">
        <div class="title">Đơn hàng gần đây</div>
        <div class="product-recently">
            <table class="order">
                <tbody>
                    <?php
                    $query = "SELECT n.id, n.amount, (n.amount * a.price) as money, u.name as name_user, a.title as name_account, n.time 
                    FROM notification_buy n, user u, store_account_children a 
                    WHERE n.user_id = u.id AND n.account_id = a.id
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
                <td class="infor"><i class="fa-solid fa-money-bill"></i> <span class="name-user">Công Tèo</span> vừa nạp <span class="money">12k</span></td>
                <td class="time">
                    <p>12 giờ trước</p>
                </td>
            </table>
        </div>
    </div>
</div>