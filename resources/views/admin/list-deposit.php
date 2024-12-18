<div class="list-deposit-container">
    <div class="flex">
        <div class="title">Quản Lý Nạp Thẻ</div>
        <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
    </div>
    <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Tài Khoản</th>
            <th>Họ Tên</th>
            <th>Loại Thẻ</th>
            <th>Serial</th>
            <th>Pin</th>
            <th>Số Tiền</th>
            <th>Thời Gian</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $banks = post_api(base_url("api/bank/GetAllBank.php?limit_start=5&status=W"), api_verify())->banks;

            array_map(function ($bank, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $bank->username ?></td>
                    <td><?= $bank->name ?></td>
                    <td><?= $bank->type ?></td>
                    <td><?= $bank->serial ?></td>
                    <td><?= $bank->pin ?></td>
                    <td><?= number_format($bank->amount) ?>đ</td>
                    <td><?= timeAgo($bank->time_created) ?></td>
                    <td>
                        <form action="" method="post">
                            <button class="success" name="deposit_type" type="submit" value="S">Thành Công</button>
                            <button class="failed" name="deposit_type" type="submit" value="F">Thất Bại</button>
                            <input type="text" value="<?= hash_encode($bank->id) ?>" name="deposit_type_id" hidden>
                            <input type="text" value="<?= hash_encode($bank->id_user) ?>" name="user_id" hidden>
                        </form>
                    </td>
                </tr>
            <?php }, $banks, array_map_length($banks)); ?>
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
    let table_users = document.querySelector('.list-deposit-container table tbody');
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-deposit?" . hash_encode("search") . "=") ?>" + input_find.value, true);
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
        xhr.open("GET", "<?= base_url("php/limit-deposit?" . hash_encode("limit-deposit")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
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

<?php
if (input_post("deposit_type") && input_post("deposit_type_id") && input_post("user_id")) {
    $deposit_type = check_string(input_post("deposit_type"));
    $deposit_type_id = hash_decode(check_string(input_post("deposit_type_id")));
    $user_id = hash_decode(check_string(input_post("user_id")));
    $tableBank = "bank";
    $tableUser = "user";

    if (!$deposit_type_id) {
        show_notification("error", "Lỗi rồi bạn ơi!");
    }

    $respon = post_api(base_url("api/bank/Deposit.php"), api_verify([
        "id_bank" => $deposit_type_id,
        "id_user" => $user_id,
        "status" => $deposit_type
    ]));
    if ($respon->status == "error") show_notification("error", $respon->message);

    reload();
}
?>