<div class="deposit-banner-container">
    <form class="deposit-banner" method="post">
        <div class="title">NẠP THẺ</div>
        <div class="deposit card-type">
            <label for="">Loại thẻ</label>
            <select name="card_type" id="">
                <option value="">-- Chọn loại thẻ --</option>
                <option value="viettel">Viettel</option>
                <option value="vinaphone">Vinaphone</option>
                <option value="mobifone">Mobifone</option>
                <option value="vietnamobile">Vietnamobile</option>
                <option valsue="zing">Zing</option>
            </select>
        </div>
        <div class="deposit money-type">
            <label for="">Mệnh giá</label>
            <select name="money_type" id="">
                <option value="">-- Chọn mệnh giá --</option>
                <option value="10000">10,000đ</option>
                <option value="20000">20,000đ</option>
                <option value="50000">50,000đ</option>
                <option value="100000">100,000đ</option>
                <option value="200000">200,000đ</option>
                <option value="500000">500,000đ</option>
            </select>
        </div>
        <div class="deposit card-serial">
            <label for="">Serial</label>
            <input type="text" name="serial" oninput="checkCardExist()" placeholder="Nhập serial thẻ">
        </div>
        <div class="deposit card-pin">
            <label for="">Pin</label>
            <input type="text" name="pin" oninput="checkCardExist()" placeholder="Nhập mã thẻ">
        </div>
        <div class="error"></div>
        <div class="discount">Số tiền thực nhận: 0 </div>
        <div class="submit"><button type="submit">Nạp Thẻ</button></div>
    </form>
    <div class="notice-banner">
        <div class="title">Lưu Ý</div>
        <div class="message">Vui lòng chọn đúng mệnh giá và điền chính xác thông tin pin và serial để được cộng tiền nhanh nhất</div>
    </div>
</div>

<script>
    let serialDeposit = document.querySelector(".deposit-banner-container .card-serial input");
    let pinDeposit = document.querySelector(".deposit-banner-container .card-pin input");
    let errorDeposit = document.querySelector(".deposit-banner-container .error");
    let isErrorCard = false;

    const checkCardExist = () => {
        if (serialDeposit.value.length < 10) {
            errorDeposit.innerHTML = "Mã serial không hợp lệ!";
            isErrorCard = true;
        } else if (pinDeposit.value.length < 10) {
            errorDeposit.innerHTML = "Mã pin không hợp lệ!";
            isErrorCard = true;
        } else {
            errorDeposit.innerHTML = "";
        }
    }

    let typeMoneyDeposit = document.querySelector('.deposit-banner-container .deposit.money-type select');
    let moneyReceivedDeposit = document.querySelector('.deposit-banner-container .discount');
    const formatter = new Intl.NumberFormat('en');
    typeMoneyDeposit.addEventListener("change", () => {
        moneyReceivedDeposit.innerHTML = typeMoneyDeposit != "" ?
            "Số tiền thực nhận: " + formatter.format(typeMoneyDeposit.value * <?= discount($call_db->site("discount")) ?>) + "đ" :
            "Số tiền thực nhận: 0";
    })

    let formDeposit = document.querySelector('.deposit-banner-container .deposit-banner');
    let btnFormDeposit = document.querySelector('.deposit-banner-container button[type="submit"]')

    formDeposit.onsubmit = (e) => {
        e.preventDefault();
    }

    btnFormDeposit.addEventListener('click', () => {
        if (isErrorCard) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "<?= base_url("php/deposit"); ?>", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = JSON.parse(xhr.response);

                        notification(data['status'], data["message"], "");
                        btnFormDeposit.classList.add("enable");
                    }
                }
            }

            let form = new FormData(formDeposit);
            form.append("id", "<?= hash_encode(session_get("information")['id']); ?>");
            xhr.send(form);
        }
    })
</script>