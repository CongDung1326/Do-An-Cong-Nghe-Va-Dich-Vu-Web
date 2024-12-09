<?php
$money = 0;
if (session_get("information")) {
    $id = session_get("information")['id'];

    $result = post_api(base_url("api\user\GetUserById.php?id_user=$id"), api_verify())['user'];
    $money = $result->money;
}
?>

<div class="sidebar-container hidden">
    <div class="close-icon"><i class="fa-solid fa-x"></i></div>
    <div class="side-top">
        <div class="logo"><img src="<?= base_url(site("logo")) ?>" alt=""></div>
        <div class="shop-name"><?= site("name_shop") ?></div>
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
                <a href="<?= base_url("client/shop"); ?>">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <div class="text">Mua Sản Phẩm</div>
                </a>
            </div>
            <div class="item">
                <div class="drop-down" href="<?= base_url("client/purchased") ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <div class="text">Lịch Sử Mua Hàng</div>
                    <i class="fa-solid fa-chevron-up right"></i>
                </div>
                <ul>
                    <li>
                        <a href="<?= base_url("client/purchased") ?>">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <div class="text">Tài Khoản Clone</div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url("client/purchased-lol") ?>">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <div class="text">Tài Khoản Liên Minh</div>
                        </a>
                    </li>
                </ul>
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

    <!-- <div class="side side-user">
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
    </div> -->
</div>

<script>
    let btnClose = document.querySelector(".sidebar-container .close-icon");
    let containerBanner = document.querySelector('.sidebar-container');

    btnClose.addEventListener("click", () => {
        containerBanner.classList.add("hidden");
    })

    let dropDownSidebar = document.querySelector(".sidebar-container .drop-down");
    let iconDropDownSidebar = document.querySelector('.sidebar-container .drop-down .right');
    let listDropDownSidebar = document.querySelector('.sidebar-container .item ul');

    dropDownSidebar.addEventListener('click', () => {
        if (iconDropDownSidebar.classList.contains("fa-chevron-down")) {
            iconDropDownSidebar.classList.remove("fa-chevron-down");
            iconDropDownSidebar.classList.add("fa-chevron-up");
            listDropDownSidebar.style.display = "none";
        } else {
            iconDropDownSidebar.classList.remove("fa-chevron-up");
            iconDropDownSidebar.classList.add("fa-chevron-down");
            listDropDownSidebar.style.display = "block";
        }
    })
</script>