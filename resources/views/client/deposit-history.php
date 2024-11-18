<div class="deposit-history-container">
    <div class="deposit-history">
        <div class="title">LỊCH SỬ NẠP THẺ</div>

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
                $query = "SELECT b.id, b.type, b.amount, b.serial, b.pin, b.status, b.time_created, b.comment 
                FROM bank b, user u 
                WHERE b.user_id = u.id AND b.user_id = $id
                ORDER BY FIELD(b.status, 'W','S','F')";

                $banks = $call_db->get_list($query);

                array_map(function ($bank, $count) {

                ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $bank['type'] ?></td>
                        <td><?= number_format($bank['amount']) ?>đ</td>
                        <td><?= $bank['serial'] ?></td>
                        <td><?= $bank['pin'] ?></td>
                        <td><?php
                            switch (strtolower($bank['status'])) {
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
                        <td><?= timeAgo($bank['time_created']) ?></td>
                        <td><?= $bank['comment'] ?></td>
                    </tr>
                <?php }, $banks, array_map_length($banks)); ?>
            </tbody>
        </table>
    </div>
</div>