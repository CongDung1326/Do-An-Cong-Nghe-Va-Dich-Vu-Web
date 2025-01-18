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
        $target_dir = "public/images/client/";
        $target_dir_remove = __DIR__ . "/../../../";
        $name_images = upload_images($target_dir, $images);

        switch ($name_images) {
            case 1:
                show_notification("error", "Vui lòng chọn đúng định dạng ảnh");
            case 2:
                show_notification("error", "Có gì đó sai sai!");
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
    if ($respon->errCode == 8) remove_upload_images($target_dir_remove, $name_images);
    if ($respon->status == "error") show_notification("error", $respon->message);

    if ($check_is_set_image) {
        $old_images = list_separator($item->image);
        $target_dir = __DIR__ . "/../../../";

        remove_upload_images($target_dir, $old_images);
    }
    show_notification("success", "Sửa thành công!", base_url_admin("manage-item-lol"));
}
?>