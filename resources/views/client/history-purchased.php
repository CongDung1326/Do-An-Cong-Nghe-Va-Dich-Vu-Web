<div class="history-purchased-container">
    <div class="flex">
        <div class="title">Lịch Sử Mua Hàng</div>
        <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
    </div>
    <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
    <table class="history-purchased">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Thanh Toán</th>
                <th>Mã Giao Dịch</th>
                <th>Thời Gian</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_id = session_get("information")['id'];
            $query = "SELECT b.id, b.store_account_children_id, b.amount, s.title, b.money as price, b.time, b.unique_code
            FROM notification_buy b, store_account_children s 
            WHERE b.store_account_children_id = s.id AND b.user_id = $user_id AND b.is_show = 'T'
            ORDER BY time DESC LIMIT 5";

            $buys = $call_db->get_list($query);
            array_map(function ($buy, $count) {  ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $buy['title'] ?></td>
                    <td><?= $buy['amount'] ?></td>
                    <td><?= number_format($buy['price']) ?>đ</td>
                    <td><?= $buy['unique_code'] ?></td>
                    <td><?= timeAgo($buy['time']) ?></td>
                    <td>
                        <button class="check"><a href="<?= base_url("client/check-purchased/" . hash_encode($buy['id'])) ?>">Kiểm Tra Sản Phẩm</a></button>
                        <button class="delete" value="<?= hash_encode($buy['id']) ?>">Xoá</button>
                    </td>
                </tr>
            <?php }, $buys, array_map_length($buys)); ?>
        </tbody>
    </table>
    <div class="change-page">
        <div class="prev" onclick="prevPage()"><button>Sau</button></div>
        <input type="number" disabled value="1">
        <div class="next" onclick="nextPage()"><button>Trước</button></div>
    </div>
</div>

<script>
    let input_find = document.querySelector(".flex .find input[type='text']");
    let table_users = document.querySelector('.history-purchased-container table tbody');
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-purchased?" . hash_encode("search") . "=") ?>" + input_find.value, true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;

                    table_users.innerHTML = data;
                    input_page.value = 1;
                    limit.value = "";
                    showYesNo();
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
        xhr.open("GET", "<?= base_url("php/limit-purchased?" . hash_encode("limit-pucharsed")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
            limit.value, true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = xhr.response;

                    if (limit.value >= 2) {
                        table_users.innerHTML = data;
                    }
                    input_find.value = "";
                    showYesNo();
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

    const showYesNo = () => {
        let btnDelete = document.querySelectorAll(".history-purchased-container button.delete");
        btnDelete.forEach((button) => {
            button.addEventListener('click', () => {
                notificationYesNo("warning", "Bạn có chắc muốn xoá không?", "<?= base_url("client/remove-purchased/") ?>" + button.value);
            })
        })
    }
    showYesNo();
</script>