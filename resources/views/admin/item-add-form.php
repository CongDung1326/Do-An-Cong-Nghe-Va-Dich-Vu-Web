<div class="category-add-container">
    <div class="title">Thêm Tài Khoản Random</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="item_username">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="item_password">
        </div>
        <div class="category">
            <label for="">Sản Phẩm</label>
            <select name="item_product" id="">
                <?php
                $products = get_api(base_url("api/product/GetAllProduct.php"))->products;

                array_map(function ($product) { ?>
                    <option value="<?= hash_encode($product->id) ?>"><?= $product->title ?></option>
                <?php }, $products); ?>
            </select>
        </div>
        <button type="submit">Thêm</button>
    </form>
</div>

<?php
if (input_post("item_username") && input_post("item_password") && input_post("item_product")) {
    $item_username = check_string(input_post("item_username"));
    $item_password = check_string(input_post("item_password"));
    $item_product = check_string(hash_decode(input_post("item_product")));
    $data = [
        "username" => $item_username,
        "password" => $item_password,
        "id_product" => $item_product
    ];

    $add_random = post_api(base_url("api/account/AddAccountRandom.php"), $data);
    if ($add_random->errCode == 8) {
        show_notification("warning", $add_random->message);
    }

    show_notification("success", $add_random->message, base_url_admin("manage-item"));
}
?>