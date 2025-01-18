<?php
$id = check_string(hash_decode(input_get("id")));

$respon = post_api(base_url("api/product/GetProductById.php"), api_verify(["id_product" => $id]));
if ($respon->status == "error") redirect(base_url_admin());
$product = $respon->product;
?>

<div class="product-add-container">
    <div class="title">Sửa Sản Phẩm</div>
    <form method="post" class="form-product-add">
        <div class="product">
            <label for="">Tiêu Đề</label>
            <input type="text" name="product_title" value="<?= $product->title; ?>">
        </div>
        <div class="product">
            <label for="">Bình Luận</label>
            <input type="text" name="product_comment" value="<?= $product->comment; ?>">
        </div>
        <div class="product">
            <label for="">Giá</label>
            <input type="text" name="product_price" value="<?= $product->price; ?>">
        </div>
        <div class="product">
            <label for="">Chuyên Mục</label>
            <select name="product_category" id="">
                <?php
                $categorys = get_api(base_url("api/category/GetAllCategory.php"))->categories;

                array_map(function ($category) {
                    global $product;

                    $isSelect = ($product->store_account_parent_id == $category->id) ? "selected" : "";
                ?>
                    <option <?= $isSelect; ?> value="<?= hash_encode($category->id) ?>"><?= $category->name ?></option>
                <?php }, $categorys); ?>
            </select>
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("product_title") && input_post("product_comment") && input_post("product_price") && input_post("product_category")) {
    $product_title = check_string(input_post("product_title"));
    $product_comment = check_string(input_post("product_comment"));
    $product_price = check_string(input_post("product_price"));
    $product_category = check_string(hash_decode(input_post("product_category")));

    $respon = post_api(base_url("api/product/EditProduct.php"), api_verify([
        "title" => $product_title,
        "comment" => $product_comment,
        "price" => $product_price,
        "id_category" => $product_category,
        "id_product" => $id
    ]));
    if ($respon->status == "error") show_notification("error", $respon->message);
    show_notification("success", "Sửa thành công!", base_url_admin("manage-store"));
}
?>