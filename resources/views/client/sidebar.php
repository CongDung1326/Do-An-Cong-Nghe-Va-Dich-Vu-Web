<div class="sidebar-container hidden">
    <div class="close-icon"><i class="fa-solid fa-x"></i></div>
    <div class="side-top">
        <div class="logo"><img src="https://marketplace.canva.com/EAFaFUz4aKo/2/0/1600w/canva-yellow-abstract-cooking-fire-free-logo-JmYWTjUsE-Q.jpg" alt=""></div>
        <div class="shop-name">DonVauShop</div>
    </div>

    <div class="side side-user">
        <div class="top">SỐ DƯ 0Đ</div>

        <div class="bottom">
            <div class="item">
                <i class="fa-solid fa-house"></i>
                <div class="text">Bảng Điều Khiển</div>
            </div>
            <div class="item">
                <i class="fa-solid fa-cart-shopping"></i>
                <div class="text">Mua Tài Khoản</div>
            </div>
            <div class="item">
                <i class="fa-solid fa-book"></i>
                <div class="text">Mua Tài Liệu</div>
            </div>
            <div class="item">
                <i class="fa-brands fa-square-facebook"></i>
                <div class="text">Mua Fanpage/Group</div>
            </div>
            <div class="item">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <div class="text">Lịch Sử Mua Hàng</div>
            </div>
        </div>
    </div>

    <div class="side side-user">
        <div class="top">NẠP TIỀN</div>

        <div class="bottom">
            <div class="item">
                <i class="fa-solid fa-sd-card"></i>
                <div class="text">Nạp Thẻ</div>
            </div>
        </div>
    </div>

    <div class="side side-user">
        <div class="top">KHÁC</div>

        <div class="bottom">
            <div class="item">
                <i class="fa-brands fa-blogger"></i>
                <div class="text">Bài Viết</div>
            </div>
            <div class="item">
                <i class="far fa-question-circle"></i>
                <div class="text">FAQ</div>
            </div>
            <div class="item">
                <i class="fas fa-address-book"></i>
                <div class="text">Liên Hệ</div>
            </div>
        </div>
    </div>
</div>

<script>
    let btnClose = document.querySelector(".sidebar-container .close-icon");
    let containerBanner = document.querySelector('.sidebar-container');
    let btnOpen = document.querySelector(".nav-container .nav-left li i");

    btnClose.addEventListener("click", () => {
        containerBanner.classList.add("hidden");
    })
    btnOpen.addEventListener("click", () => {
        containerBanner.classList.remove("hidden");
    })
</script>