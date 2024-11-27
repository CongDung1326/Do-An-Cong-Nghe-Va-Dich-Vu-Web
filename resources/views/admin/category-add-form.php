<div class="category-add-container">
    <div class="title">Thêm Chuyên Mục</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Chuyên Mục</label>
            <input type="text" name="category_name">
        </div>
        <button type="submit">Thêm</button>
    </form>
</div>

<?php
if (input_post("category_name")) {
    $category_name = check_string(input_post("category_name"));
    $table = "store_account_parent";

    $call_db->insert($table, [
        "name" => $category_name
    ]);
    redirect(base_url_admin("manage-store"));
}
?>