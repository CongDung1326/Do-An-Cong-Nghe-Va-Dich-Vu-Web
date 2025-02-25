<?php
$text_error = "";
if (input_post("input_username") && input_post("input_password")) {
    $username = input_post("input_username");
    $password = input_post("input_password");

    $result = post_api(base_url("api-v2/users/login_admin.php"), [
        "username" => $username,
        "password" => $password
    ]);

    if ($result['errCode'] == 0) {
        $user = $result['user'];
        session_set("information_admin", [
            "userId" => $user['userId'],
            "timeLogin" => time(),
            "isLogin" => true
        ]);
    } else
        $text_error = $result['message'];
}
?>

<div class="main-content p-0 d-flex align-items-center" style="min-height: calc(100vh - 66px);">
    <div class="container-fluid d-flex justify-content-center">
        <!-- Form login -->
        <form class="d-flex needs-validation flex-column p-3 bg-light text-center" style="border-radius: 10px 0px 0px 10px; width: 40%;" method="post">
            <div class="logo"><img style="width: 100px; height: 100px;" src="<?= base_url() . site_v2("SETTING_LOGO") ?>" alt=""></div>
            <h2 class="title font-weight-bold mb-3">Login Admin Panel</h2>
            <div class="mb-3 text-left">
                <label for="" class="form-label">Username</label>
                <input type="text" name="input_username" class="form-control" required>
            </div>
            <div class="mb-2 text-left">
                <label for="" class="form-label">Password</label>
                <input type="text" name="input_password" class="form-control" required>
            </div>
            <div class="mb-3 text-left text-danger font-weight-bold"><?= $text_error ?></div>
            <div class="mb-3 text-right"><a href="">Quên Mật Khẩu</a></div>
            <button class="btn btn-success" type="submit">Đăng nhập</button>
        </form>
        <!-- Theme -->
        <div class="w-50"><img style="border-radius: 0px 10px 10px 0px;" class="full-size-image" src="<?= base_url() . "assets/storage/theme-login-admin.png" ?>" alt=""></div>
    </div>
</div>