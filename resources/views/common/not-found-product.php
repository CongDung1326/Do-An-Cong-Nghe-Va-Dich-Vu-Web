<?php
if (isset($not_found) && isset($not_found['list']) && isset($not_found['title'])):
    if (count($not_found['list']) == 0): ?>
        <div class="not-found-product">
            <img src="<?= base_url("assets/storage/Product Not Found.png") ?>" alt="">
            <div class="title"><?= $not_found['title']; ?></div>
        </div>
<?php endif;
endif; ?>