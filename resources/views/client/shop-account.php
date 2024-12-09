<?php
$call_shop_account = get_api(base_url("api/category/GetAllCategory.php"))['categories'];
?>

<?php if ($call_shop_account): ?>
    <div class="shop-account-container">
        <?php array_map(function ($value) { ?>
            <div class="wapper-table">
                <table class="shop-account">
                    <thead class="top">
                        <tr>
                            <th><?= $value->name ?></th>
                            <th>Hiện có</th>
                            <th>Đã bán</th>
                            <th>Giá</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bottom">
                        <?php
                        $call_data_shop_account = get_api(base_url("api\product\GetAllProductByIdCategory.php?id_category={$value->id}"))['products'];

                        array_map(function ($value_children) {
                            $check_sold = $value_children->store > 0 ? true : false;
                        ?>
                            <tr>
                                <td class="title">
                                    <p><?= $value_children->title ?></p>
                                    <div class="comment"><?= $value_children->comment ?></div>
                                </td>
                                <td class="store"><span><i class="fas fa-luggage-cart mr-1"></i> <?= number_format($value_children->store) ?></span></td>
                                <td class="sold"><span><i class="fas fa-cart-arrow-down mr-1"></i> <?= number_format($value_children->sold) ?></span></td>
                                <td class="price"><span><i class="far fa-money-bill-alt mr-1"></i> <?= number_format($value_children->price) ?>đ</span></td>
                                <td class="tools <?= $check_sold ? "have-item" : "sold-out" ?>"><span><?= $check_sold ? "<i class='fa-solid fa-cart-shopping'></i> MUA HÀNG" : "<i class='fas fa-frown mr-1'></i> HẾT HÀNG"; ?></span></td>
                                <td style="display: none;"><input type="text" value="<?= hash_encode($value_children->id); ?>" hidden></td>
                            </tr>
                        <?php }, $call_data_shop_account); ?>
                    </tbody>
                </table>
            </div>
        <?php }, $call_shop_account); ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . "/form-buy.php"; ?>