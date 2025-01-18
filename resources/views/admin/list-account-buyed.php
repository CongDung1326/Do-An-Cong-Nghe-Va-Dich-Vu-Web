<div class="list-account-container">
    <div class="tools">
        <div class="title">Tài Khoản Đã Bán</div>
        <div class="add">
            <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
        </div>
    </div>
    <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
    <table>
        <thead>
            <th>STT</th>
            <th>Tên Tài Khoản</th>
            <th>Mật Khẩu</th>
            <th>Tên Tài Khoản Mua</th>
            <th>Mã Giao Dịch</th>
            <th>Trạng Thái</th>
            <th>Chức Năng</th>
        </thead>
        <tbody>
            <?php
            $accounts = post_api(base_url("api/account/GetAllAccountBuyed.php?limit_start=5"), api_verify())->accounts;

            array_map(function ($account, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $account->username ?></td>
                    <td><?= $account->password ?></td>
                    <td><?= $account->user_username ?></td>
                    <td><?= $account->unique_code ?></td>
                    <td><?= $account->is_sold == "T" ? "Đã Bán" : "Chưa Bán"; ?></td>
                    <td>
                        <button class="success"><a href="<?= base_url_admin("edit-account-buyed/" . hash_encode($account->id)) ?>">Chỉnh Sửa</a></button>
                        <button class="failed" value="<?= hash_encode($account->id) ?>">Xoá</button>
                    </td>
                </tr>
            <?php }, $accounts, array_map_length($accounts)); ?>
        </tbody>
    </table>
    <div class="change-page">
        <div class="prev" onclick="prevPage()"><button>Sau</button></div>
        <input type="number" disabled value="1">
        <div class="next" onclick="nextPage()"><button>Trước</button></div>
    </div>
</div>

<script>
    let input_find = document.querySelector(".tools .find input[type='text']");
    let table_users = document.querySelector('.list-account-container table tbody');
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-account-buyed?" . hash_encode("search") . "=") ?>" + input_find.value, true);
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
        xhr.open("GET", "<?= base_url("php/limit-account-buyed?" . hash_encode("limit-account")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
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

    const showYesNo = () => {
        let btnDelete = document.querySelectorAll(".list-account-container button.failed");
        btnDelete.forEach((button) => {
            button.addEventListener('click', () => {
                notificationYesNo("warning", "Bạn có chắc muốn xoá không?", "<?= base_url_admin("remove-account-buyed/") ?>" + button.value);
            })
        })
    }
    showYesNo();
</script>