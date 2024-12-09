<div class="form-settings-container">
    <div class="title">Cài Đặt</div>
    <form method="post" class="form-settings" enctype="multipart/form-data">
        <div class="category">
            <label for="">Tên Website</label>
            <input type="text" name="title" value="<?= site("title"); ?>">
        </div>
        <div class="category">
            <label for="">Description</label>
            <input type="text" name="description" value="<?= site("description"); ?>">
        </div>
        <div class="category">
            <label for="">Logo <span>Lưu ý: Nếu không chọn gì thì đồng nghĩa là giữ nguyên ảnh!</span></label>
            <input type="file" name="logo">
        </div>
        <div class="category">
            <label for="">Keyword</label>
            <input type="text" name="keyword" value="<?= site("keyword"); ?>">
        </div>
        <div class="category">
            <label for="">Tên Shop</label>
            <input type="text" name="name_shop" value="<?= site("name_shop"); ?>">
        </div>
        <div class="category">
            <label for="">Chiết Khấu</label>
            <input type="text" name="discount" value="<?= site("discount"); ?>">
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (input_post("title") && input_post("description") && input_post("keyword") && input_post("name_shop") && input_post("discount")) {
    $title = check_string(input_post("title"));
    $description = check_string(input_post("description"));
    $keyword = check_string(input_post("keyword"));
    $name_shop = check_string(input_post("name_shop"));
    $discount = check_string(input_post("discount"));
    $image = $_FILES['logo'];
    $time = time();
    $target_dir = "assets/storage/";
    $check_image_exist = !empty($image['name']) ? true : false;
    $table = "settings";

    if (!is_numeric($discount)) show_notification("error", "Chiết khấu vui lòng nhập số!");
    if ($check_image_exist) {
        $target_file = $target_dir . $time . basename($image['name']);
        $image_file_type = check_image(strtolower($target_file));
        if (!$image_file_type) show_notification("error", "Vui lòng chọn đúng định dạng ảnh!");
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            show_notification("error", "Có gì đó sai sai!");
        } else {
            $old_images = $call_db->get_row("SELECT value FROM settings WHERE name='logo'")['value'];
            $target_dir = __DIR__ . "/../../../";
            if (file_exists($target_dir . $old_images)) {
                unlink($target_dir . $old_images);
            }

            $call_db->update($table, [
                "value" => $target_file
            ], "name='logo'");
        }
    }
    $result = [
        "title" => $title,
        "description" => $description,
        "keyword" => $keyword,
        "name_shop" => $name_shop,
        "discount" => $discount,
    ];
    foreach ($result as $key => $value) {
        $call_db->update($table, [
            "value" => $value
        ], "name = '$key'");
    }
    show_notification("success", "Cập nhật thành công!");
}
?>