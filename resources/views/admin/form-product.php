<div class="form-product-container">
    <div class="tools">
        <div class="title">Quản Lý Sản Phẩm</div>
        <div class="add"><a href="<?= base_url_admin("product-add") ?>">Thêm Sản Phẩm</a></div>
    </div>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tiêu Đề</th>
                <th>Bình Luận</th>
                <th>Hàng Hiện Có</th>
                <th>Đã Bán</th>
                <th>Giá</th>
                <th>Tên Chuyên Mục</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $products = get_api(base_url("api/product/GetAllProduct.php"))['products'];
            array_map(function ($product, $count) { ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $product->title ?></td>
                    <td><?= $product->comment ?></td>
                    <td><?= $product->store ?></td>
                    <td><?= $product->sold ?></td>
                    <td><?= number_format($product->price) ?>đ</td>
                    <td><?= $product->name ?></td>
                    <td>
                        <button class="success"><a href="<?= base_url_admin("product-edit/" . hash_encode($product->id)) ?>">Sửa</a></button>
                        <button class="failed"><a href="<?= base_url_admin("product-remove/" . hash_encode($product->id)) ?>">Xoá</a></button>
                    </td>
                </tr>
            <?php }, $products, array_map_length($products)); ?>
        </tbody>
    </table>
</div>