<?php
$id = check_string(hash_decode(input_get("id")));

$query = "SELECT u.username, u.name, u.age, u.email, u.number_phone, u.money, u.role_id
FROM user u
WHERE u.id = $id";
$user = $call_db->get_row($query);

if ($call_db->num_rows($query) != 1) redirect(base_url_admin());
?>

<div class="user-edit-container">
    <div class="title">Sửa Người Dùng: <?= $user['username'] ?></div>
    <form method="post" class="form-user-edit">
        <div class="user">
            <label for="">Tên</label>
            <input type="text" name="user_name" value="<?= $user['name']; ?>">
        </div>
        <div class="user">
            <label for="">Email</label>
            <input type="email" name="user_email" value="<?= $user['email']; ?>">
        </div>
        <div class="user">
            <label for="">Mật Khẩu</label>
            <input type="password" name="user_password" value="">
            <span style="font-size: var(--font-size-0)">Nếu không nhập gì đồng nghĩa mật khẩu vẫn vậy!</span>
        </div>
        <div class="user">
            <label for="">Số Điện Thoại</label>
            <input type="text" name="user_number_phone" value="<?= $user['number_phone']; ?>">
        </div>
        <div class="user">
            <label for="">Quyền Hạng</label>
            <select name="user_role_id" id="">
                <?php
                $is_admin = $user['role_id'] == "2" ? "selected" : "";
                ?>
                <option value="0">Người Dùng</option>
                <option <?= $is_admin ?> value="2">Admin</option>
            </select>
        </div>
        <div class="user-flex">
            <div class="user">
                <label for="">Hiện Có</label>
                <input type="text" value="<?= number_format($user['money']) ?>đ" disabled>
            </div>
            <div class="user">
                <label for="">Đã Tiêu</label>
                <?php
                $used = $call_db->get_row("SELECT SUM(money) as result FROM notification_buy WHERE user_id = $id;")['result'];
                ?>
                <input type="text" value="<?= number_format($used) ?>Đ" disabled>
            </div>
            <div class="user">
                <label for="">Tổng Đã Nạp</label>
                <?php
                $deposit = $call_db->get_row("SELECT SUM(amount) as result FROM bank WHERE user_id = $id AND status = 'S';")['result'];
                ?>
                <input type="text" value="<?= number_format($deposit) ?>đ" disabled>
            </div>
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>
<div class="user-edit-container flex">
    <form action="" method="post" class="deposit">
        <div class="title">Cộng Tiền</div>

        <div class="user">
            <label for="">Số Lượng</label>
            <input type="number" name="user_deposit" min="1" placeholder="Nhập số tiền muốn cộng...">
        </div>

        <button type="submit">Cộng tiền</button>
    </form>
    <form action="" method="post" class="deduct-money">
        <div class="title">Trừ Tiền</div>

        <div class="user">
            <label for="">Số Lượng</label>
            <input type="number" name="user_deduct_money" min="1" placeholder="Nhập số tiền muốn trừ...">
        </div>

        <button type="submit">Trừ tiền</button>
    </form>
</div>

<?php
if (input_post("user_deposit")) {
    $user_deposit = check_string(input_post("user_deposit"));
    $table = "user";

    $call_db->update($table, [
        'money' => $user['money'] + $user_deposit,
    ], "id = $id");
    show_notification("success", "Buff tiền thành công!");
}
if (input_post("user_deduct_money")) {
    $user_deduct_money = check_string(input_post("user_deduct_money"));
    $table = "user";

    $call_db->update($table, [
        'money' => $user['money'] - $user_deduct_money,
    ], "id = $id");
    show_notification("success", "Trừ tiền thành công!");
}

if (input_post("user_password")) {
    $user_password = check_string(input_post("user_password"));
    $table = "user";

    $call_db->update($table, [
        'password' => $user_password,
    ], "id = $id");
    redirect(base_url_admin("manage-user"));
}

if (input_post("user_name") && input_post("user_email") && input_post("user_number_phone") && input_post("user_role_id")) {
    $user_name = check_string(input_post("user_name"));
    $user_email = check_string(input_post("user_email"));
    $user_number_phone = check_string(input_post("user_number_phone"));
    $user_role_id = check_string(input_post("user_role_id"));
    $table = "user";

    if (!is_numeric($user_number_phone)) show_notification("error", "Số điện thoại vui lòng nhập số!");

    $call_db->update($table, [
        'name' => $user_name,
        'email' => $user_email,
        'number_phone' => $user_number_phone,
        'role_id' => $user_role_id
    ], "id = $id");
    redirect(base_url_admin("manage-user"));
}
?>