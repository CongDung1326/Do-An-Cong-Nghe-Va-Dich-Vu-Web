<div class="user-list-container">
    <div class="flex">
        <div class="title">Quản Lý Người Dùng</div>
        <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
    </div>
    <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
    <div class="wrapper">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tài Khoản</th>
                    <th>Ví</th>
                    <th>Admin</th>
                    <th>Chức Năng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = post_api(base_url("api/user/GetAllUser.php?limit_start=5"), api_verify())->users;
                array_map(function ($user, $count) { ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td>
                            <ul>
                                <li><b>Tên đăng nhập:</b> <?= $user->username ?></li>
                                <li><b>Địa chỉ Email:</b> <?= $user->email ?></li>
                                <li><b>Số điện thoại:</b> <?= $user->number_phone ?></li>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <li><b>Số dư khả dụng:</b> <?= number_format($user->money) ?>đ</li>
                                <li><b>Tổng số tiền nạp:</b> <?= number_format($user->total_money) ?>đ</li>
                            </ul>
                        </td>
                        <td><?= $user->role_id == '2' ? "Có" : "Không"; ?></td>
                        <td>
                            <button class="success"><a href="<?= base_url_admin("user-edit/" . hash_encode($user->id)) ?>">Sửa</a></button>
                            <button class="failed" value="<?= hash_encode($user->id) ?>">Xoá</button>
                        </td>
                    </tr>
                <?php }, $users, array_map_length($users)); ?>
            </tbody>
        </table>
    </div>
    <div class="change-page">
        <div class="prev" onclick="prevPage()"><button>Sau</button></div>
        <input type="number" disabled value="1">
        <div class="next" onclick="nextPage()"><button>Trước</button></div>
    </div>
</div>

<script>
    let input_find = document.querySelector(".flex .find input[type='text']");
    let table_users = document.querySelector('.user-list-container table tbody');
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-user?" . hash_encode("search") . "=") ?>" + input_find.value, true);
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
        xhr.open("GET", "<?= base_url("php/limit-user?" . hash_encode("limit-user")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
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
        let btnDelete = document.querySelectorAll(".user-list-container button.failed");
        btnDelete.forEach((button) => {
            button.addEventListener('click', () => {
                notificationYesNo("warning", "Bạn có chắc muốn xoá không?", "<?= base_url_admin("user-remove/") ?>" + button.value);
            })
        })
    }
    showYesNo();
</script>