<?php
$id = input_get("id");

$query = "SELECT image FROM account_lol WHERE id = $id";
$account = $call_db->get_row($query);

if (!isset($account['image'])) redirect(base_url_admin());
?>

<div class="list-image-container">
    <div class="list-image">
        <div class="list-image">
            <?php
            $images = list_separator($account['image']);
            foreach ($images as $src) { ?>
                <img src="<?= base_url($src); ?>" alt="">
            <?php } ?>
        </div>
    </div>
</div>