<?php
$id = check_string(hash_decode(input_get("id")));

$respon = get_api(base_url("api/account/GetAccountLOLByIdAccount.php?id=$id"));
if ($respon->status == "error") redirect(base_url_admin());

$item = $respon->account;
?>

<div class="category-add-container">
    <div class="title">Sửa Hàng</div>
    <form method="post" class="form-category-add" enctype="multipart/form-data">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="lol_username" value="<?= $item->username ?>">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="lol_password" value="<?= $item->password ?>">
        </div>
        <div class="category">
            <label for="">Số Tướng</label>
            <input type="text" name="lol_number_char" value="<?= $item->number_char ?>">
        </div>
        <div class="category">
            <label for="">Số Skin</label>
            <input type="text" name="lol_number_skin" value="<?= $item->number_skin ?>">
        </div>
        <div class="category">
            <label for="">Rank</label>
            <select name="lol_rank" id="">
                <?php
                $get_list_rank = get_api(base_url("api/images/GetAllImagesRankLOL.php"))->images;

                array_map(function ($rank) {
                    global $item;
                    $is_selected = ($item->rank_lol_id == $rank->id) ? "selected" : "";
                ?>
                    <option value="<?= $rank->id ?>" <?= $is_selected ?>><?= $rank->name ?></option>
                <?php }, $get_list_rank); ?>
            </select>
        </div>
        <div class="category">
            <label for="">Price</label>
            <input type="text" name="lol_price" value="<?= $item->price ?>">
        </div>
        <div class="category">
            <label for="">Hình Ảnh <span>Lưu ý: Nếu không chọn gì thì đồng nghĩa là giữ nguyên ảnh!</span></label>
            <input type="file" name="lol_images[]" multiple>
        </div>
        <button type="submit">Sửa</button>
    </form>
</div>

<?php
if (isset($_POST['lol_rank'])) {
    $username = check_string(input_post("lol_username"));
    $password = check_string(input_post("lol_password"));
    $number_char = check_string(input_post("lol_number_char"));
    $number_skin = check_string(input_post("lol_number_skin"));
    $rank = check_string(input_post("lol_rank"));
    $price = check_string(input_post("lol_price"));

    $check_is_set_image = !empty($_FILES['lol_images']['name'][0]) ? true : false;
    if ($check_is_set_image) {
        $images = $_FILES['lol_images'];
        $name_images = [];
        $time = time();
        $target_dir = "public/images/client/";

        for ($i = 0; $i < count($images['name']); $i++) {
            $target_file = $target_dir . $time . basename($images['name'][$i]);
            $image_file_type = check_image(strtolower($target_file));

            if (!$image_file_type) show_notification("error", "Vui lòng chọn đúng định dạng ảnh!");
            if (move_uploaded_file($images['tmp_name'][$i], $target_file)) {
                array_push($name_images, $target_dir . $time . basename($images['name'][$i]));
            } else {
                show_notification("error", "Có gì đó sai sai!");
            }
        }
    }

    if ($check_is_set_image) {
        $old_images = list_separator($item->image);
        $target_dir = __DIR__ . "/../../../";

        foreach ($old_images as $image) {
            if (file_exists($target_dir . $image)) {
                unlink($target_dir . $image) ? "" : show_notification("warning", "Vui lòng đừng nghịch bậy bạ!");
            }
        }
    }

    $data = [
        "username" => $username,
        "password" => $password,
        "number_char" => $number_char,
        "number_skin" => $number_skin,
        "id_rank" => $rank,
        "price" => $price,
        "images" => $check_is_set_image ? implode(",", $name_images) : "",
        "id_account" => $id,
    ];
    $respon = post_api(base_url("api/account/EditAccountLOL.php"), $data);

    if ($respon->errCode == 1) show_notification("warning", "Vui lòng nhập đầy đủ");
    if ($respon->status == "error") show_notification("error", $respon->message);

    show_notification("success", "Sửa thành công!", base_url_admin("manage-item-lol"));
}
?>