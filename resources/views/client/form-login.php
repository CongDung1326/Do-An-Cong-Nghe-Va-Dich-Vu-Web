<?php
$title = "";
if (input_post("username") && input_post("password")) {
    $username = check_string(input_post("username"));
    $password = check_string(input_post("password"));

    $data = [
        "username" => $username,
        "password" => $password
    ];
    $login = post_api(base_url("api/user/Login.php"), $data);

    if ($login['status'] == "success") {
        $row = $login['user'];
        session_set("information", [
            "id" => $row->id,
            "name" => $row->name,
            "role" => $row->role_id,
            "avatar" => $row->avatar
        ]);

        redirect(base_url());
    } else {
        $title = $login['message'];
    }
}
?>

<div class="form-login-wrapper">
    <div class="form-login-container">
        <div class="logo"><img src="https://marketplace.canva.com/EAFaFUz4aKo/2/0/1600w/canva-yellow-abstract-cooking-fire-free-logo-JmYWTjUsE-Q.jpg" alt=""></div>

        <form class="form-login" action="" method="post">
            <div class="title">Đăng Nhập</div>

            <div class="inputs">
                <label for="">Tên đăng nhập</label>
                <input type="text" name="username" placeholder="Enter your username">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" id="" placeholder="Enter your password">
            </div>
            <div class="error-title"><?= $title ?></div>
            <div class="forgot-password"><a href="">Quên Mật Khẩu</a></div>
            <button type="submit" style="--color: #4285F4">Đăng Nhập</button>
        </form>

        <button style="--color: #FBBC05;"><a href="<?= base_url("client/register"); ?>">Đăng Ký</a></button>
    </div>
</div>