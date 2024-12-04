<div class="order-sold-container">
    <div class="tools">
        <div class="title">Đơn Hàng Đã Bán</div>
        <div class="add">
            <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
        </div>
    </div>
    <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Tiền</th>
            <th>Số Lượng</th>
            <th>Mã Giao Dịch</th>
            <th>Thời Gian</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $query = "SELECT store_account_children_id, account_lol_id FROM notification_buy ORDER BY time DESC LIMIT 5";
            $notifications = $call_db->get_list($query);
            $orders = [];

            array_map(function ($notification) {
                global $call_db, $orders;

                if (isset($notification['store_account_children_id'])) {
                    $store_account_children_id = $notification['store_account_children_id'];
                    $query = "SELECT b.id, b.amount, b.money, b.time, s.title, b.unique_code
                    FROM store_account_children s, notification_buy b 
                    WHERE s.id = b.store_account_children_id AND s.id = $store_account_children_id";

                    array_push($orders, $call_db->get_row($query));
                } else {
                    $account_lol_id = $notification['account_lol_id'];
                    $query = "SELECT b.id, b.amount, b.money, b.time, a.id as title, b.unique_code
                    FROM account_lol a, notification_buy b 
                    WHERE a.id = b.account_lol_id AND a.id = $account_lol_id";

                    array_push($orders, $call_db->get_row($query));
                }
            }, $notifications);

            array_map(function ($order, $count) {
                $order['title'] = is_numeric($order['title']) ? "Acc Liên Minh #" . $order['title'] : $order['title'];
            ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $order['title'] ?></td>
                    <td><?= number_format($order['money']) ?>đ</td>
                    <td><?= $order['amount'] ?></td>
                    <td><?= $order['unique_code'] ?></td>
                    <td><?= timeAgo($order['time']) ?></td>
                    <td>
                        <button class="failed"><a href="<?= base_url_admin("order-remove/" . hash_encode($order['id'])) ?>">Xoá</a></button>
                    </td>
                </tr>
            <?php }, $orders, array_map_length($orders)); ?>
        </tbody>
    </table>
    <div class="change-page">
        <div class="prev" onclick="prevPage()"><button>Sau</button></div>
        <input type="number" disabled value="1">
        <div class="next" onclick="nextPage()"><button>Trước</button></div>
    </div>
</div>

<script>
    let table_users = document.querySelector('.order-sold-container table tbody');
    let input_find = document.querySelector(".tools .find input[type='text']");
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-order-sold?" . hash_encode("search") . "=") ?>" + input_find.value, true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;

                    table_users.innerHTML = data;
                    input_page.value = 1;
                    limit.value = "";
                }
            }
        }

        xhr.send();
    });

    limit.addEventListener('input', () => {
        limitUser();
    })

    const limitUser = () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/limit-order-sold?" . hash_encode("limit-order")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
            limit.value, true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = xhr.response;

                    if (limit.value >= 2) {
                        table_users.innerHTML = data;
                    }
                    input_find.value = "";
                }
            }
        }

        xhr.send();
    }


    const nextPage = () => {
        input_page.value++;
        limitUser();
    }
    const prevPage = () => {
        input_page.value--;
        if (input_page.value <= 0) {
            input_page.value = 1;
        }
        limitUser();
    }
</script>