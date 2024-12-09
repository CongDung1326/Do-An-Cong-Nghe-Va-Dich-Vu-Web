<div class="deposit-history-container">
    <div class="deposit-history">
        <div class="flex">
            <div class="title">LỊCH SỬ NẠP THẺ</div>
            <div class="find"><span>Tìm Kiếm: </span><input type="text" value=""></div>
        </div>
        <div class="limit"><span>Hiển Thị (Thấp Nhất 2): </span><input type="number" min="1" value="5"></div>
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nhà Mạng</th>
                    <th>Mệnh Giá</th>
                    <th>Mã Thẻ</th>
                    <th>Pin</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian</th>
                    <th>Lý Do</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $id = session_get("information")['id'];

                $banks = post_api(base_url("api\bank\GetAllBankByIdUser.php?limit_start=5"), api_verify(["id_user" => $id]))['banks'];

                array_map(function ($bank, $count) {

                ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $bank->type ?></td>
                        <td><?= number_format($bank->amount) ?>đ</td>
                        <td><?= $bank->serial ?></td>
                        <td><?= $bank->pin ?></td>
                        <td><?php
                            switch (strtolower($bank->status)) {
                                case "s":
                                    echo "thành công";
                                    break;
                                case "w":
                                    echo "đang đợi";
                                    break;
                                case "f":
                                    echo "không thành công";
                                    break;
                            }
                            ?></td>
                        <td><?= timeAgo($bank->time_created) ?></td>
                        <td><?= $bank->comment ?></td>
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
</div>

<script>
    let input_find = document.querySelector(".flex .find input[type='text']");
    let table_users = document.querySelector('.deposit-history-container table tbody');
    let input_page = document.querySelector(".change-page input[type='number']");
    let limit = document.querySelector(".limit input[type='number']");

    input_find.addEventListener("input", () => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "<?= base_url("php/find-deposit-history?" . hash_encode("search") . "=") ?>" + input_find.value, true);
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
        xhr.open("GET", "<?= base_url("php/limit-deposit-history?" . hash_encode("limit-deposit")) ?>" + "=" + input_page.value + "&" + "<?= hash_encode("limit") ?>" + "=" +
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