<div class="category-add-container">
    <div class="title">Thêm Tài Khoản LOL</div>
    <form method="post" class="form-category-add" enctype="multipart/form-data">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="lol_username">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="lol_password">
        </div>
        <div class="category">
            <label for="">Số Tướng</label>
            <input type="text" name="lol_number_char">
        </div>
        <div class="category">
            <label for="">Số Skin</label>
            <input type="text" name="lol_number_skin">
        </div>
        <div class="category">
            <label for="">Rank</label>
            <select name="lol_rank" id="">
                <?php
                $get_list_rank = $call_db->get_list("SELECT * FROM images WHERE type='rank_lol'");

                array_map(function ($rank) { ?>
                    <option value="<?= $rank['id'] ?>"><?= $rank['name'] ?></option>
                <?php }, $get_list_rank); ?>
            </select>
        </div>
        <div class="category">
            <label for="">Price</label>
            <input type="text" name="lol_price">
        </div>
        <div class="category">
            <label for="">Hình Ảnh</label>
            <input type="file" name="lol_images[]" multiple>
        </div>
        <button type="submit">Thêm</button>
    </form>
</div>

<?php
if (
    input_post("lol_username")
    && input_post("lol_password")
    && input_post("lol_number_char")
    && input_post("lol_number_skin")
    && input_post("lol_rank")
    && input_post("lol_price")
    && isset($_FILES['lol_images'])
) {
    $username = check_string(input_post("lol_username"));
    $password = check_string(input_post("lol_password"));
    $number_char = check_string(input_post("lol_number_char"));
    $number_skin = check_string(input_post("lol_number_skin"));
    $rank = check_string(input_post("lol_rank"));
    $table_account = "account";
    $table_account_lol = "account_lol";
    $price = check_string(input_post("lol_price"));
    $images = $_FILES['lol_images'];
    $name_images = [];
    $time = time();
    $target_dir = "public/images/client/";

    if (!is_numeric($number_char)) show_notification("error", "Số tướng vui lòng nhập số!");
    if (!is_numeric($number_skin)) show_notification("error", "Số skin vui lòng nhập số!");
    if (!is_numeric($rank)) show_notification("error", "Vui lòng không nghịch bậy bạ gì nhé!");
    if (!is_numeric($price)) show_notification("error", "Số tiền vui lòng nhập số!");
    if ($price < 0 || $number_char < 0 || $number_skin < 0) show_notification("error", "Không được thấp hơn 0");
    for ($i = 0; $i < count($images['name']); $i++) {
        $target_file = $target_dir . $time . basename($images['name'][$i]);
        $image_file_type = check_image(strtolower($target_file));

        if (!$image_file_type) show_notification("error", "Vui lòng chọn đúng định dạng ảnh");
        if (move_uploaded_file($images['tmp_name'][$i], $target_file)) {
            array_push($name_images, "public/images/client/" . $time . basename($images['name'][$i]));
        } else {
            show_notification("error", "Có gì đó sai sai!");
        }
    }

    try {
        $call_db->insert($table_account, [
            "username" => $username,
            "password" => $password,
            "is_sold" => "F",
            "type" => "lol",
        ]);
    } catch (Exception) {
        show_notification("warning", "Trùng mã tài khoản!");
    }
    $id_account = $call_db->get_row("SELECT id FROM $table_account WHERE username = '$username'")['id'];
    $call_db->insert($table_account_lol, [
        "number_char" => $number_char,
        "number_skin" => $number_skin,
        "rank_lol_id" => $rank,
        "price" => $price,
        "account_id" => $id_account,
        "image" => implode(",", $name_images)
    ]);

    show_notification("success", "Thêm thành công!", base_url_admin("manage-item-lol"));
}
?>