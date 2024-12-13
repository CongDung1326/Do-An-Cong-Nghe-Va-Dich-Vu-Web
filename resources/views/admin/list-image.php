<?php
$id = input_get("id");

$account = get_api(base_url("api/account/GetAccountLOLByIdAccount.php?id=$id"))['account'];

if (!isset($account->image)) redirect(base_url_admin());
?>

<div class="list-image-container">
    <div class="list-image">
        <div class="list-image">
            <?php
            $images = list_separator($account->image);
            foreach ($images as $src) { ?>
                <img src="<?= base_url($src); ?>" alt="">
            <?php } ?>
        </div>
    </div>
</div>