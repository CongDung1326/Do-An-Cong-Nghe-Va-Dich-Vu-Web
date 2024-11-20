<?php
$id = hash_decode(input_get("id"));

$query = "SELECT * FROM store_account_parent WHERE id=$id";
$category = $call_db->get_row($query);
?>

<div class="category-add-container">
    <div class="title">Sửa Chuyên Mục</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Chuyên Mục</label>
            <input type="text" name="category_name" value="<?= $category['name'] ?>">
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("category_name")) {
    $category_name = input_post("category_name");
    $table = "store_account_parent";

    $call_db->update($table, [
        "name" => $category_name
    ], "id=$id");
    redirect(base_url_admin("manage-store"));
}
?>