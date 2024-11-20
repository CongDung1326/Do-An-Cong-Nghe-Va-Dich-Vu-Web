<div class="category-add-container">
    <div class="title">Thêm Chuyên Mục</div>
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
                $queryProduct = "SELECT * FROM store_account_children";
                $products = $call_db->get_list($queryProduct);

                array_map(function ($product) { ?>
                    <option value="<?= hash_encode($product['id']) ?>"><?= $product['title'] ?></option>
                <?php }, $products); ?>
            </select>
        </div>
        <button type="submit">Thêm</button>
    </form>
</div>

<?php
if (input_post("item_username") && input_post("item_password") && input_post("item_product")) {
    $item_username = input_post("item_username");
    $item_password = input_post("item_password");
    $item_product = hash_decode(input_post("item_product"));
    $table = "account";
    $table_product = "store_account_children";

    if (!is_numeric($item_product)) show_notification("warning", "Vui lòng không nghịch gì nhé!");

    $store = $call_db->get_row("SELECT store FROM $table_product WHERE id=$item_product")['store'];
    $call_db->insert($table, [
        "username" => $item_username,
        "password" => $item_password,
        "store_account_children_id" => $item_product,
        "is_sold" => "F",
    ]);
    $call_db->update($table_product, [
        'store' => $store + 1
    ], "id=$item_product");
    redirect(base_url_admin("manage-item"));
}
?>