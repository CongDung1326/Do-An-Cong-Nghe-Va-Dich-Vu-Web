<div class="form-buy-container hidden">
    <form class="form-buy" method="post">
        <div class="icon-close"><i class="fa-solid fa-x"></i></div>
        <div class="title">Mua Sản Phẩm</div>
        <div class="inputs">
            <label for="">Tên sản phẩm</label>
            <input type="text" disabled>
            <label for="">Số lượng</label>
            <input type="number" min="1" name="amount" placeholder="Enter your amount">
        </div>

        <button class="<?= session_get("information") ? "buy" : "" ?>"><?= session_get("information") ? "Mua Sản Phẩm" : "Vui Lòng Đăng Nhập"; ?></button>
    </form>
</div>

<script>
    let btnCloseFormBuy = document.querySelector(".form-buy-container .icon-close");
    let containerFormBuy = document.querySelector('.form-buy-container');
    let btnOpenFormBuy = document.querySelectorAll(".shop-account-container .tools span");
    let formBuyDataId = document.querySelectorAll("table.shop-account .bottom input[hidden]");
    let nameFormBuy = document.querySelector('.form-buy-container .inputs input[disabled]');
    let inputFormBuy = document.querySelector('.form-buy-container button[class="buy"]');
    let inputAmountFormBuy = document.querySelector('.form-buy-container input[type="number"]');
    let price = 0,
        lastClick;

    btnCloseFormBuy.addEventListener("click", () => {
        containerFormBuy.classList.add("hidden");
    })
    btnOpenFormBuy.forEach((value, index) => {
        value.addEventListener("click", () => {
            inputAmountFormBuy.value = null; // Set default value
            inputFormBuy.innerHTML = "Mua Sản Phẩm"; // Set default value
            lastClick = formBuyDataId[index].value; // Get id last click

            let xhr = new XMLHttpRequest();
            xhr.open('GET', "<?= base_url("php/get-buy-item?id=") ?>" + formBuyDataId[index].value, true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = JSON.parse(xhr.response);

                        nameFormBuy.value = data['title'];
                        price = data['price'];
                        containerFormBuy.classList.remove("hidden");
                    }
                }
            }

            xhr.send();
        })
    });

    const showPriceToBuy = (e) => {
        let amount = inputAmountFormBuy.value;
        const formatter = new Intl.NumberFormat('en');

        if (amount > 0) {
            inputFormBuy.innerHTML = "Mua Với Giá " + formatter.format(amount * price) + "đ";
        } else {
            inputFormBuy.innerHTML = "Số lượng không được nhỏ hơn 0!";
        }
    }

    inputAmountFormBuy.addEventListener("change", showPriceToBuy);
    inputAmountFormBuy.addEventListener("input", showPriceToBuy);

    let formBuy = document.querySelector('form.form-buy');
    let message = "";

    formBuy.onsubmit = (e) => {
        e.preventDefault();
    }

    inputFormBuy.addEventListener('click', () => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "<?= base_url("php/buy-item")  ?>", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = JSON.parse(xhr.response);

                    notification(data['status'], data["message"], "");
                    inputFormBuy.classList.remove("buy")
                }
            }
        }

        let formData = new FormData(formBuy);
        formData.append("id", lastClick);
        xhr.send(formData);
    })
</script>