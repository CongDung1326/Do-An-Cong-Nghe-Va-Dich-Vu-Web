<?php
$id = check_string(hash_decode(input_get("id")));

$query = "SELECT a.id, a.username, a.password, l.id as name, l.rank_lol_id, a.is_sold, l.number_char, l.number_skin, i.name as rank, l.price, l.image
            FROM account a, account_lol l, images i
            WHERE (a.id = l.account_id AND l.rank_lol_id = i.id) AND a.is_sold = 'F' AND a.type = 'lol' AND a.id = $id;";
$item = $call_db->get_row($query);

if ($call_db->num_rows($query) != 1) redirect(base_url_admin());
?>

<div class="category-add-container">
    <div class="title">Sửa Hàng</div>
    <form method="post" class="form-category-add" enctype="multipart/form-data">
        <div class="category">
            <label for="">Tên Tài Khoản</label>
            <input type="text" name="lol_username" value="<?= $item['username'] ?>">
        </div>
        <div class="category">
            <label for="">Mật Khẩu</label>
            <input type="text" name="lol_password" value="<?= $item['password'] ?>">
        </div>
        <div class="category">
            <label for="">Số Tướng</label>
            <input type="text" name="lol_number_char" value="<?= $item['number_char'] ?>">
        </div>
        <div class="category">
            <label for="">Số Skin</label>
            <input type="text" name="lol_number_skin" value="<?= $item['number_skin'] ?>">
        </div>
        <div class="category">
            <label for="">Rank</label>
            <select name="lol_rank" id="">
                <?php
                $get_list_rank = $call_db->get_list("SELECT * FROM images WHERE type='rank_lol'");

                array_map(function ($rank) {
                    global $item;
                    $is_selected = ($item['rank_lol_id'] == $rank['id']) ? "selected" : "";
                ?>
                    <option value="<?= $rank['id'] ?>" <?= $is_selected ?>><?= $rank['name'] ?></option>
                <?php }, $get_list_rank); ?>
            </select>
        </div>
        <div class="category">
            <label for="">Price</label>
            <input type="text" name="lol_price" value="<?= $item['price'] ?>">
        </div>
        <div class="category">
            <label for="">Hình Ảnh <span>Lưu ý: Nếu không chọn gì thì đồng nghĩa là giữ nguyên ảnh!</span></label>
            <input type="file" name="lol_images[]" multiple>
        </div>
        <button type="submit">Sửa</button>
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
) {
    $username = check_string(input_post("lol_username"));
    $password = check_string(input_post("lol_password"));
    $number_char = check_string(input_post("lol_number_char"));
    $number_skin = check_string(input_post("lol_number_skin"));
    $rank = check_string(input_post("lol_rank"));
    $table_account = "account";
    $table_account_lol = "account_lol";
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

    if (!is_numeric($number_char)) show_notification("error", "Số tướng vui lòng nhập số!");
    if (!is_numeric($number_skin)) show_notification("error", "Số skin vui lòng nhập số!");
    if (!is_numeric($rank)) show_notification("error", "Vui lòng không nghịch bậy bạ gì nhé!");
    if (!is_numeric($price)) show_notification("error", "Số tiền vui lòng nhập số!");
    if ($price < 0 || $number_char < 0 || $number_skin < 0) show_notification("error", "Không được thấp hơn 0");
    if ($check_is_set_image) {
        $old_images = list_separator($call_db->get_row("SELECT image FROM account_lol WHERE account_id = $id")['image']);
        $target_dir = __DIR__ . "/../../../";

        foreach ($old_images as $image) {
            if (file_exists($target_dir . $image)) {
                unlink($target_dir . $image) ? "" : show_notification("warning", "Vui lòng đừng nghịch bậy bạ!");
            }
        }

        $call_db->update($table_account_lol, [
            "image" => implode(",", $name_images)
        ], "account_id = $id");
    }

    try {
        $call_db->update($table_account, [
            "username" => $username,
            "password" => $password,
            "is_sold" => "F",
            "type" => "lol",
        ], "id = $id");
    } catch (Exception) {
        show_notification("warning", "Trùng mã tài khoản!");
    }
    $id_account = $call_db->get_row("SELECT id FROM $table_account WHERE username = '$username'")['id'];
    $call_db->update($table_account_lol, [
        "number_char" => $number_char,
        "number_skin" => $number_skin,
        "rank_lol_id" => $rank,
        "price" => $price,
        "account_id" => $id_account,
    ], "account_id = $id");

    show_notification("success", "Sửa thành công!", base_url_admin("manage-item-lol"));
}
?>