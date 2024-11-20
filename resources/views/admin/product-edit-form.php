<?php
$id = hash_decode(input_get("id"));

$query = "SELECT sc.id, sc.title, sc.comment, sc.store, sc.sold, sc.price, sp.name FROM store_account_children sc, store_account_parent sp WHERE (sc.store_account_parent_id = sp.id) AND sc.id = $id";
$product = $call_db->get_row($query);
?>

<div class="product-add-container">
    <div class="title">Sửa Sản Phẩm</div>
    <form method="post" class="form-product-add">
        <div class="product">
            <label for="">Tiêu Đề</label>
            <input type="text" name="product_title" value="<?= $product['title']; ?>">
        </div>
        <div class="product">
            <label for="">Bình Luận</label>
            <input type="text" name="product_comment" value="<?= $product['comment']; ?>">
        </div>
        <div class="product">
            <label for="">Giá</label>
            <input type="text" name="product_price" value="<?= $product['price']; ?>">
        </div>
        <div class="product">
            <label for="">Chuyên Mục</label>
            <select name="product_category" id="">
                <?php
                $queryCategory = "SELECT * FROM store_account_parent";
                $categorys = $call_db->get_list($queryCategory);

                array_map(function ($category) {
                    global $product;

                    $isSelect = ($product['name'] == $category['name']) ? "selected" : "";
                ?>
                    <option <?= $isSelect; ?> value="<?= hash_encode($category['id']) ?>"><?= $category['name'] ?></option>
                <?php }, $categorys); ?>
            </select>
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("product_title") && input_post("product_comment") && input_post("product_price") && input_post("product_category")) {
    $product_title = input_post("product_title");
    $product_comment = input_post("product_comment");
    $product_price = input_post("product_price");
    $product_category = hash_decode(input_post("product_category"));
    $table = "store_account_children";

    if (!is_numeric($product_category)) show_notification("warning", "Vui lòng không nghịch gì bậy bạ!");
    if (!is_numeric($product_price)) show_notification("warning", "Giá tiền thì vui lòng nhập số thôi!");

    $call_db->update($table, [
        "title" => $product_title,
        "comment" => $product_comment,
        "price" => $product_price,
        "store_account_parent_id" => $product_category,
    ], "id=$id");
    redirect(base_url_admin("manage-store"));
}
?>