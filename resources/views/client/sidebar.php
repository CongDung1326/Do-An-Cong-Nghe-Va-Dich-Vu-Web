<?php
$money = 0;
if (session_get("information")) {
    $id = session_get("information")['id'];

    $query = "SELECT money FROM user WHERE id=$id";
    $result = $call_db->get_row($query);
    $money = $result['money'];
}
?>

<div class="sidebar-container hidden">
    <div class="close-icon"><i class="fa-solid fa-x"></i></div>
    <div class="side-top">
        <div class="logo"><img src="https://marketplace.canva.com/EAFaFUz4aKo/2/0/1600w/canva-yellow-abstract-cooking-fire-free-logo-JmYWTjUsE-Q.jpg" alt=""></div>
        <div class="shop-name"><?= $call_db->site("name_shop") ?></div>
    </div>

    <div class="side side-user">
        <div class="top">SỐ DƯ <?= number_format($money); ?>đ</div>

        <div class="bottom">
            <div class="item">
                <a href="<?= base_url(); ?>">
                    <i class="fa-solid fa-house"></i>
                    <div class="text">Bảng Điều Khiển</div>
                </a>
            </div>
            <div class="item">
                <a href="<?= base_url("client/puchased") ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <div class="text">Lịch Sử Mua Hàng</div>
                </a>
            </div>
        </div>
    </div>

    <div class="side side-user">
        <div class="top">NẠP TIỀN</div>

        <div class="bottom">
            <div class="item">
                <a href="<?= base_url("client/deposit") ?>">
                    <i class="fa-solid fa-sd-card"></i>
                    <div class="text">Nạp Thẻ</div>
                </a>
            </div>
        </div>
    </div>

    <div class="side side-user">
        <div class="top">KHÁC</div>

        <div class="bottom">
            <div class="item">
                <a href="">
                    <i class="fa-brands fa-blogger"></i>
                    <div class="text">Bài Viết</div>
                </a>
            </div>
            <div class="item">
                <a href="">
                    <i class="far fa-question-circle"></i>
                    <div class="text">FAQ</div>
                </a>
            </div>
            <div class="item">
                <a href="">
                    <i class="fas fa-address-book"></i>
                    <div class="text">Liên Hệ</div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let btnClose = document.querySelector(".sidebar-container .close-icon");
    let containerBanner = document.querySelector('.sidebar-container');

    btnClose.addEventListener("click", () => {
        containerBanner.classList.add("hidden");
    })
</script>