<?php
$title = "";
if (input_post("username") && input_post("name") && input_post("password") && input_post("password_verify") && input_post("email")) {
    $username = check_string(input_post("username"));
    $name = check_string(input_post("name"));
    $password = check_string(input_post("password"));
    $password_verify = check_string(input_post("password_verify"));
    $email = check_string(input_post("email"));

    $data = [
        "username" => $username,
        "password" => $password,
        "password_verify" => $password_verify,
        "email" => $email,
        "name" => $name,
    ];
    $respon = post_api(base_url("api/user/Register.php"), $data);
    if ($respon->status == "error") $title = $respon->message;
    else
        redirect(base_url("client/login"));
}
?>

<div class="form-register-wrapper">
    <div class="back"><a href="<?= base_url() ?>"><i class="fa-solid fa-reply"></i></a></div>
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
            <button type="submit" style="--color: #FBBC05;">Đăng Ký</button>
            <div class="have-account">Đã có tài khoản? <a href="<?= base_url("client/login"); ?>">Đăng nhập</a></div>
        </form>
    </div>
</div>