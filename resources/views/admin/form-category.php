<div class="form-category-container">
    <div class="tools">
        <div class="title">Danh Sách Chuyên Mục</div>
        <div class="add"><a href="<?= base_url_admin("category-add") ?>">Thêm Chuyên Mục</a></div>
    </div>
    <div class="wrapper">
        <table>
            <thead>
                <th>STT</th>
                <th>Tên Chuyên Mục</th>
                <th>Thao Tác</th>
            </thead>
            <tbody>
                <?php
                $categorys = get_api(base_url("api/category/GetAllCategory.php"))->categories;
                array_map(function ($category, $count) { ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $category->name ?></td>
                        <td>
                            <button class="success"><a href="<?= base_url_admin("category-edit/" . hash_encode($category->id)) ?>">Sửa</a></button>
                            <button class="failed"><a href="<?= base_url_admin("category-remove/" . hash_encode($category->id)) ?>">Xoá</a></button>
                        </td>
                    </tr>
                <?php }, $categorys, array_map_length($categorys)); ?>
            </tbody>
        </table>
    </div>
</div>