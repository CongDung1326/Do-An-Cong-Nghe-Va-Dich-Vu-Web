<?php
$id = hash_decode(input_get("id"));

$query = "SELECT a.id, a.username, a.password, s.title FROM account a, store_account_children s WHERE a.store_account_children_id = s.id AND a.id=$id";
$item = $call_db->get_row($query);
?>

<div class="category-add-container">
    <div class="title">Sửa Hàng</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="item_username" value="<?= $item['username'] ?>">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="item_password" value="<?= $item['password'] ?>">
        </div>
        <div class="category">
            <label for="">Chuyên Mục</label>
            <select name="product_category" id="">
                <?php
                $queryProduct = "SELECT * FROM store_account_children";
                $products = $call_db->get_list($queryProduct);
                array_map(function ($product) {
                    global $item;
                    $isSelect = ($product['title'] == $item['title']) ? "selected" : "";
                ?>
                    <option <?= $isSelect; ?> value="<?= hash_encode($product['id']) ?>"><?= $product['title'] ?></option>
                <?php }, $products);
                ?>
            </select>
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("item_username") && input_post("item_password")) {
    $item_username = input_post("item_username");
    $item_password = input_post("item_password");
    $table = "account";

    $call_db->update($table, [
        "username" => $item_username,
        "password" => $item_password
    ], "id = $id");
    redirect(base_url_admin("manage-item"));
}
?>