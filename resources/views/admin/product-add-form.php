<div class="product-add-container">
    <div class="title">Thêm Sản Phẩm</div>
    <form method="post" class="form-product-add">
        <div class="product">
            <label for="">Tiêu Đề</label>
            <input type="text" name="product_title">
        </div>
        <div class="product">
            <label for="">Bình Luận</label>
            <input type="text" name="product_comment">
        </div>
        <div class="product">
            <label for="">Giá</label>
            <input type="text" name="product_price">
        </div>
        <div class="product">
            <label for="">Chuyên Mục</label>
            <select name="product_category" id="">
                <?php
                $categorys = get_api(base_url("api/category/GetAllCategory.php"))['categories'];
                array_map(function ($category) { ?>
                    <option value="<?= hash_encode($category->id) ?>"><?= $category->name ?></option>
                <?php }, $categorys); ?>
            </select>
        </div>
        <button type="submit">Thêm</button>
    </form>
</div>

<?php
if (input_post("product_title") && input_post("product_comment") && input_post("product_price") && input_post("product_category")) {
    $product_title = check_string(input_post("product_title"));
    $product_comment = check_string(input_post("product_comment"));
    $product_price = check_string(input_post("product_price"));
    $product_category = check_string(hash_decode(input_post("product_category")));

    $respon = post_api(base_url("api/product/AddProduct.php"), api_verify([
        "title" => $product_title,
        "comment" => $product_comment,
        "price" => $product_price,
        "id_category" => $product_category
    ]));
    if ($respon['status'] == "error") show_notification("error", $respon['message']);
    redirect(base_url_admin("manage-store"));
}
?>