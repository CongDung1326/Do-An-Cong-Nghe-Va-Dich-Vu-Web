<?php
$id = check_string(hash_decode(input_get("id")));
$respon = get_api(base_url("api/account/GetAccountRandomById.php?id=$id"));
if ($respon->status == "error") redirect(base_url_admin());

$item = $respon->account;
?>

<div class="category-add-container">
    <div class="title">Sửa Hàng</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="item_username" value="<?= $item->username ?>">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="item_password" value="<?= $item->password ?>">
        </div>
        <div class="category">
            <label for="">Chuyên Mục</label>
            <select name="item_product" id="">
                <?php
                $products = get_api(base_url("api/product/GetAllProduct.php"))->products;
                array_map(function ($product) {
                    global $item;
                    $isSelect = ($product->title == $item->title) ? "selected" : "";
                ?>
                    <option <?= $isSelect; ?> value="<?= hash_encode($product->id) ?>"><?= $product->title ?></option>
                <?php }, $products);
                ?>
            </select>
        </div>
        <button type="submit">Sửa</button>
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
        "id_product" => $item_product,
        "id_account" => $id
    ];

    if (!is_numeric($item_product)) show_notification("warning", "Vui lòng không nghịch bậy bạ!");
    $respon = post_api(base_url("api/account/EditAccountRandom.php"), $data);
    if ($respon->status == "error") show_notification("warning", $respon->message);

    redirect(base_url_admin("manage-item"));
}
?>