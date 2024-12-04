<?php
$id = check_string(hash_decode(input_get("id")));

$query = "SELECT a.id, a.username, a.password, s.title FROM account a, store_account_children s WHERE a.store_account_children_id = s.id AND a.is_sold = 'F' AND a.id=$id";
$item = $call_db->get_row($query);

if ($call_db->num_rows($query) != 1) redirect(base_url_admin());
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
            <select name="item_product" id="">
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
if (input_post("item_username") && input_post("item_password") && input_post("item_product")) {
    $item_username = check_string(input_post("item_username"));
    $item_password = check_string(input_post("item_password"));
    $item_product = check_string(hash_decode(input_post("item_product")));

    if (!is_numeric($item_product)) show_notification("warning", "Vui lòng không nghịch bậy bạ!");
    $table = "account";

    $call_db->update($table, [
        "username" => $item_username,
        "password" => $item_password,
        "store_account_children_id" => $item_product
    ], "id = $id");
    redirect(base_url_admin("manage-item"));
}
?>