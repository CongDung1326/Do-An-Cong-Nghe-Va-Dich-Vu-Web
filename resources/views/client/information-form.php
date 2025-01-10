<?php
$id = hash_decode(input_get("id"));

$respon = post_api(base_url("api/user/GetUserById.php?id=$id"), api_verify());
if ($respon->status == "error") redirect(base_url());

$user = $respon->user;
$name = name_user($user->name);
?>

<div class="information-form-container">
    <div class="title">Thông Tin Người Dùng</div>
    <form class="information-form" method="post">
        <div class="information">
            <div class="input last-name">
                <label for="">Họ</label>
                <input type="text" name="first_name" value="<?= implode(" ", $name['first_name']); ?>">
                <i class="fa-regular fa-user"></i>
            </div>
            <div class="input first-name">
                <label for="">Tên</label>
                <input type="text" name="last_name" value="<?= $name['last_name']; ?>">
                <i class="fa-regular fa-user"></i>
            </div>
        </div>
        <div class="information">
            <div class="input email">
                <label for="">Email</label>
                <input type="email" name="email" value="<?= $user->email ?>">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input number-phone">
                <label for="">Số điện thoại</label>
                <input type="text" name="number_phone" value="<?= $user->number_phone ?>">
                <i class="fa-solid fa-phone"></i>
            </div>
        </div>
        <div class="information">
            <div class="input age">
                <label for="">Sinh nhật</label>
                <input type="date" name="age" value="<?= $user->age ?>">
            </div>
            <div class="input time-created">
                <label for="">Ngày tạo</label>
                <input type="date" disabled value="<?= $user->time_created ?>">
                <i class="fa-regular fa-calendar-plus"></i>
            </div>
        </div>
        <button type="submit">Chỉnh sửa</button>
    </form>
</div>

<?php
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['number_phone']) && isset($_POST['age'])) {
    $first_name = input_post("first_name");
    $last_name = input_post("last_name");
    $email = input_post("email");
    $number_phone = input_post("number_phone");
    $age = input_post("age");
    $name = $first_name . " " . $last_name;
    $id = session_get("information")['id'];

    $respon = post_api(base_url("api/user/EditUser.php"), api_verify([
        "name" => $name,
        "email" => $email,
        "number_phone" => $number_phone,
        "age" => $age,
        "id_user" => $id,
        "role_id" => $user->role_id
    ]));
    if ($respon->status == "error") {
        show_notification("error", $respon->message);
    }
    session_set("information", [
        "id" => $id,
        "name" => $name,
        "role" => $user->role_id,
        "avatar" => $user->avatar
    ]);
    show_notification("success", "Sửa thông tin thành công");
}
?>