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
                $queryCategory = "SELECT * FROM store_account_parent";
                $categorys = $call_db->get_list($queryCategory);
                array_map(function ($category) { ?>
                    <option value="<?= hash_encode($category['id']) ?>"><?= $category['name'] ?></option>
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
    $table = "store_account_children";

    if (!is_numeric($product_category)) show_notification("warning", "Vui lòng không nghịch gì bậy bạ!");
    if (!is_numeric($product_price)) show_notification("warning", "Giá tiền thì vui lòng nhập số thôi!");

    $call_db->insert($table, [
        "title" => $product_title,
        "comment" => $product_comment,
        "sold" => 0,
        "store" => 0,
        "price" => $product_price,
        "store_account_parent_id" => $product_category,
    ]);
    redirect(base_url_admin("manage-store"));
}
?>