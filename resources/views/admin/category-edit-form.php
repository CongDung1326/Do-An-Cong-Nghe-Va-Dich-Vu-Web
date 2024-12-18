<?php
$id = check_string(hash_decode(input_get("id")));

$respon = post_api(base_url("api\category\GetCategoryById.php"), ["id_category" => $id]);
$category = $respon->category;

if ($respon->status == "error") redirect(base_url_admin());
?>

<div class="category-add-container">
    <div class="title">Sửa Chuyên Mục</div>
    <form method="post" class="form-category-add">
        <div class="category">
            <label for="">Tên Chuyên Mục</label>
            <input type="text" name="category_name" value="<?= $category->name ?>">
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("category_name")) {
    $category_name = check_string(input_post("category_name"));

    post_api(base_url("api\category\EditCategory.php"), [
        "id_category" => $id,
        "name" => $category_name
    ]);
    redirect(base_url_admin("manage-store"));
}
?>