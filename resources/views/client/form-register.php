<?php
$title = "";
if (input_post("username") && input_post("name") && input_post("password") && input_post("password_verify") && input_post("email")) {
    $username = check_string(input_post("username"));
    $name = check_string(input_post("name"));
    $password = check_string(input_post("password"));
    $password_verify = check_string(input_post("password_verify"));
    $email = check_string(input_post("email"));
    $isError = false;

    if ($password != $password_verify) {
        $title = "Mật khẩu không trùng khớp!";
        $isError = true;
    }
    $query = "SELECT * FROM user WHERE username='$username'";
    if ($call_db->num_rows($query) > 0) {
        $title = "Tài khoản đã được sử dụng";
        $isError = true;
    }

    if (!$isError) {
        $call_db->insert("user", [
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "name" => $name,
            "avatar" => "assets/storage/default_avatar.jpg",
            "role_id" => 0
        ]);

        redirect(base_url("client/login"));
    }
}
?>

<div class="form-register-wrapper">
    <div class="form-register-container">
        <div class="logo"><img src="https://marketplace.canva.com/EAFaFUz4aKo/2/0/1600w/canva-yellow-abstract-cooking-fire-free-logo-JmYWTjUsE-Q.jpg" alt=""></div>

        <form class="form-register" action="" method="post">
            <div class="title">Đăng Ký</div>

            <div class="inputs">
                <label for="">Tên đăng ký</label>
                <input type="text" name="username" placeholder="Enter your username">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" id="" placeholder="Enter your password">
                <label for="">Xác nhận lại mật khẩu</label>
                <input type="password" name="password_verify" id="" placeholder="Enter your verify password">
                <label for="">Họ tên</label>
                <input type="text" name="name" id="" placeholder="Enter your namef">
                <label for="">Email</label>
                <input type="email" name="email" id="" placeholder="Enter your email">
            </div>
            <div class="error-title"><?= $title ?></div>
            <div class="have-account"><a href="<?= base_url("client/login"); ?>">Đã có tài khoản</a></div>
            <button type="submit" style="--color: #FBBC05;">Đăng Ký</button>
        </form>
    </div>
</div>