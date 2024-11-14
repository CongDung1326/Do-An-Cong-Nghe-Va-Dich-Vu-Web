<div class="form-buy-container hidden">
    <div class="form-buy">
        <div class="icon-close"><i class="fa-solid fa-x"></i></div>
        <div class="title">Mua Sản Phẩm</div>
        <div class="inputs">
            <label for="">Tên sản phẩm</label>
            <input type="text" disabled>
            <label for="">Số lượng</label>
            <input type="number" min="1" value="1" placeholder="Enter your amount">
        </div>

        <button>Mua Sản Phẩm</button>
    </div>
</div>

<script>
    let btnCloseFormBuy = document.querySelector(".form-buy-container .icon-close");
    let containerFormBuy = document.querySelector('.form-buy-container');
    let btnOpenFormBuy = document.querySelectorAll(".shop-account-container .tools.have-item span");

    btnCloseFormBuy.addEventListener("click", () => {
        containerFormBuy.classList.add("hidden");
    })
    btnOpenFormBuy.forEach(value => {
        value.addEventListener("click", () => {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', "<?= base_url("php/get-buy-item") ?>", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;

                        console.log(data);
                        containerFormBuy.classList.remove("hidden");
                    }
                }
            }

            xhr.send();
        })
    });
</script>